<?php

declare(strict_types=1);

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2026 CARL von CHIARI GmbH
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

namespace Cvc\Typo3\CvcTwig\View;

use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\View\AbstractTemplateView;

class TwigTemplateView extends AbstractTemplateView
{
    private ?ServerRequestInterface $request = null;
    private string $templateName = '';

    /** @var array<int, string> */
    private array $templateRootPaths = [];
    /** @var array<mixed> */
    private array $namespaces = [];

    public function __construct(private readonly Environment $environment, ?RenderingContextInterface $context = null)
    {
        parent::__construct($context);
    }

    public function setRequest(ServerRequestInterface $request): TwigTemplateView
    {
        $this->request = $request;

        return $this;
    }

    public function render($templateFileName = ''): string
    {
        $templatePaths = $this->getRenderingContext()->getTemplatePaths()->getTemplateRootPaths();

        $fileSystemLoader = new FilesystemLoader($templatePaths);
        foreach ($this->namespaces as $namespace => $namespacedPaths) {
            $namespacedPaths = array_reverse($namespacedPaths);
            $namespacedPaths = array_map([GeneralUtility::class, 'getFileAbsFileName'], $namespacedPaths);
            $fileSystemLoader->setPaths($namespacedPaths, $namespace);
        }
        $this->environment->setLoader($fileSystemLoader);

        return $this->environment->render($templateFileName, [
            'request' => $this->request,
            // @phpstan-ignore-next-line
            ...$this->getRenderingContext()->getVariableProvider()->getAll(),
        ]);
    }

    public function setTemplateName(string $templateName): void
    {
        $this->templateName = $templateName;
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * @param array<mixed> $templateRootPaths
     */
    public function setTemplateRootPaths(array $templateRootPaths): void
    {
        $this->templateRootPaths = $templateRootPaths;
    }

    public function addTemplateRootPath(string $templateRootPath): void
    {
        $this->templateRootPaths[] = $templateRootPath;
    }

    /**
     * @return string[]
     */
    public function getTemplateRootPaths(): array
    {
        return $this->templateRootPaths;
    }

    /**
     * @return mixed[]
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * @param array<mixed> $namespaces
     */
    public function setNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;
    }
}
