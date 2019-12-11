<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2020 CARL von CHIARI GmbH
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

require_once __DIR__.'/../.Build/vendor/autoload.php';

putenv('typo3DatabaseDriver=pdo_sqlite');
putenv('typo3DatabaseName=typo3');

$typo3PathRoot = dirname(__DIR__).'/.Build/public';
$_SERVER['TYPO3_PATH_ROOT'] = $_ENV['TYPO3_PATH_ROOT'] = $typo3PathRoot;
putenv('TYPO3_PATH_ROOT='.$typo3PathRoot);
require_once __DIR__.'/../.Build/vendor/typo3/testing-framework/Resources/Core/Build/FunctionalTestsBootstrap.php';

$helper = new class() extends \TYPO3\TestingFramework\Core\Functional\FunctionalTestCase {
    protected $testExtensionsToLoad = [
        'typo3conf/ext/cvc_twig',
    ];

    public function prepare()
    {
        $this->setUp();
    }
};

$helper->prepare();

$environment = \TYPO3\CMS\Core\Utility\GeneralUtility::getContainer()->get(\Twig\Environment::class);

$docBlockFactory = \phpDocumentor\Reflection\DocBlockFactory::createInstance();

$describer = new \Cvc\TwigDocumentor\Describer\EnvironmentDescriber(
    new \Cvc\TwigDocumentor\Describer\FunctionDescriber($docBlockFactory),
    new \Cvc\TwigDocumentor\Describer\FilterDescriber($docBlockFactory)
);
$documentation = $describer->describe($environment);
$documentation = $documentation->withSource(dirname(__DIR__).'/Classes/*');

$filterRst = $environment->render(
    'EXT:cvc_twig/Resources/Private/TwigTemplates/Documentation/filtersAndFunctions.rst.twig',
    [
        'headline' => 'Filters',
        'descriptions' => $documentation->getFilters(),
    ]
);

file_put_contents(__DIR__.'/../Documentation/Chapters/Twig-Filters/Index.rst', $filterRst);

$functionRst = $environment->render(
    'EXT:cvc_twig/Resources/Private/TwigTemplates/Documentation/filtersAndFunctions.rst.twig',
    [
        'headline' => 'Functions',
        'descriptions' => $documentation->getFunctions(),
    ]
);

file_put_contents(__DIR__.'/../Documentation/Chapters/Twig-Functions/Index.rst', $functionRst);

echo 'Documentation was generated.'.PHP_EOL;
