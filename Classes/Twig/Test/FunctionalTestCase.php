<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2021 CARL von CHIARI GmbH
 *
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Cvc\Typo3\CvcTwig\Twig\Test;

use Nimut\TestingFramework\TestCase\FunctionalTestCase as Typo3FunctionalTestCase;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\ArrayLoader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Inspired by twig/twig package src/Test/IntegrationTestCase.php and adjusted to TYPO3.
 *
 * Some Extensions require a valid TYPO3 runtime environment.
 * For example, a valid page needs to be called.
 * Therefore a Root-Page (UID=1) will be used to render the content.
 */
abstract class FunctionalTestCase extends Typo3FunctionalTestCase
{
    /**
     * @dataProvider getTests
     *
     * @param mixed $file
     * @param mixed $message
     * @param mixed $condition
     * @param mixed $templates
     * @param mixed $exception
     * @param mixed $outputs
     * @param mixed $deprecation
     */
    public function testIntegration($file, $message, $condition, $templates, $exception, $outputs, $deprecation = '')
    {
        $this->doIntegrationTest($file, $message, $condition, $templates, $exception, $outputs, $deprecation);
    }

    public function getTests()
    {
        $fixturesDir = realpath($this->getFixturesDir());

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($fixturesDir), \RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
            if (!preg_match('/\.test$/', $file)) {
                continue;
            }

            $test = file_get_contents($file->getRealpath());

            if (preg_match('/--TEST--\s*(.*?)\s*(?:--CONDITION--\s*(.*))?\s*(?:--DEPRECATION--\s*(.*?))?\s*((?:--TEMPLATE(?:\(.*?\))?--(?:.*?))+)\s*(?:--DATA--\s*(.*))?\s*--EXCEPTION--\s*(.*)/sx', $test, $match)) {
                $message = $match[1];
                $condition = $match[2];
                $deprecation = $match[3];
                $templates = self::parseTemplates($match[4]);
                $exception = $match[6];
                $outputs = [[null, $match[5], null, '']];
            } elseif (preg_match('/--TEST--\s*(.*?)\s*(?:--CONDITION--\s*(.*))?\s*(?:--DEPRECATION--\s*(.*?))?\s*((?:--TEMPLATE(?:\(.*?\))?--(?:.*?))+)--DATA--.*?--EXPECT--.*/s', $test, $match)) {
                $message = $match[1];
                $condition = $match[2];
                $deprecation = $match[3];
                $templates = self::parseTemplates($match[4]);
                $exception = false;
                preg_match_all('/--DATA--(.*?)(?:--CONFIG--(.*?))?--EXPECT--(.*?)(?=\-\-DATA\-\-|$)/s', $test, $outputs, PREG_SET_ORDER);
            } else {
                throw new \InvalidArgumentException(sprintf('Test "%s" is not valid.', str_replace($fixturesDir.'/', '', $file)));
            }

            $displayFileName = str_replace($fixturesDir.'/', '', $file);

            yield $displayFileName => [$displayFileName, $message, $condition, $templates, $exception, $outputs, $deprecation];
        }
    }

    /**
     * @return string
     */
    abstract protected function getFixturesDir();

    protected function doIntegrationTest($file, $message, $condition, $templates, $exception, $outputs, $deprecation = '')
    {
        if (!$outputs) {
            $this->markTestSkipped('no tests to run');
        }

        if ($condition) {
            eval('$ret = '.$condition.';');
            if (!$ret) {
                $this->markTestSkipped($condition);
            }
        }

        $loader = new ArrayLoader($templates);
        $typo3TwigEnvironment = GeneralUtility::getContainer()->get(Environment::class);
        $extensions = array_filter($typo3TwigEnvironment->getExtensions(), function (ExtensionInterface $extension): bool {
            return mb_strpos(get_class($extension), 'Twig\Extension') !== 0;
        });

        foreach ($outputs as $i => $match) {
            $config = array_merge([
                'cache' => false,
                'strict_variables' => true,
            ], $match[2] ? eval($match[2].';') : []);

            $twig = new Environment($loader, $config);
            $twig->addGlobal('global', 'global');
            $twig->setExtensions($extensions);

            // avoid using the same PHP class name for different cases
            $p = new \ReflectionProperty($twig, 'templateClassPrefix');
            $p->setAccessible(true);
            $p->setValue($twig, '__TwigTemplate_'.hash('sha256', uniqid(mt_rand(), true), false).'_');

            $deprecations = [];
            try {
                $prevHandler = set_error_handler(function ($type, $msg, $file, $line, $context = []) use (&$deprecations, &$prevHandler) {
                    if (E_USER_DEPRECATED === $type) {
                        $deprecations[] = $msg;

                        return true;
                    }

                    return $prevHandler ? $prevHandler($type, $msg, $file, $line, $context) : false;
                });

                $template = $twig->load('index.twig');
            } catch (\Exception $e) {
                if (false !== $exception) {
                    $message = $e->getMessage();
                    $this->assertSame(trim($exception), trim(sprintf('%s: %s', \get_class($e), $message)));
                    $last = mb_substr($message, \mb_strlen($message) - 1);
                    $this->assertTrue('.' === $last || '?' === $last, 'Exception message must end with a dot or a question mark.');

                    return;
                }

                throw new Error(sprintf('%s: %s', \get_class($e), $e->getMessage()), -1, null, $e);
            } finally {
                restore_error_handler();
            }

            $this->assertSame($deprecation, implode("\n", $deprecations));

            try {
                $output = trim($template->render(eval($match[1].';')), "\n ");
            } catch (\Exception $e) {
                if (false !== $exception) {
                    $this->assertSame(trim($exception), trim(sprintf('%s: %s', \get_class($e), $e->getMessage())));

                    return;
                }

                $e = new Error(sprintf('%s: %s', \get_class($e), $e->getMessage()), -1, null, $e);

                $output = trim(sprintf('%s: %s', \get_class($e), $e->getMessage()));
            }

            if (false !== $exception) {
                list($class) = explode(':', $exception);
                $constraintClass = class_exists('PHPUnit\Framework\Constraint\Exception') ? 'PHPUnit\Framework\Constraint\Exception' : 'PHPUnit_Framework_Constraint_Exception';
                $this->assertThat(null, new $constraintClass($class));
            }

            $expected = trim($match[3], "\n ");

            if ($expected !== $output) {
                printf("Compiled templates that failed on case %d:\n", $i + 1);

                foreach (array_keys($templates) as $name) {
                    echo "Template: $name\n";
                    echo $twig->compile($twig->parse($twig->tokenize($twig->getLoader()->getSourceContext($name))));
                }
            }
            $this->assertSame($expected, $output, $message.' (in '.$file.')');
        }
    }

    protected static function parseTemplates($test)
    {
        $templates = [];
        preg_match_all('/--TEMPLATE(?:\((.*?)\))?--(.*?)(?=\-\-TEMPLATE|$)/s', $test, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $templates[($match[1] ? $match[1] : 'index.twig')] = $match[2];
        }

        return $templates;
    }
}
