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
use TYPO3\CMS\Core\View\ViewInterface as CoreViewInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\View\AbstractTemplateView as FluidStandaloneAbstractTemplateView;
use TYPO3Fluid\Fluid\View\TemplateAwareViewInterface as FluidStandaloneTemplateAwareViewInterface;
use TYPO3Fluid\Fluid\View\ViewInterface as FluidStandaloneViewInterface;

class TwigViewAdapter implements CoreViewInterface, FluidStandaloneViewInterface, FluidStandaloneTemplateAwareViewInterface
{
    /**
     * @param TwigTemplateView $view
     */
    public function __construct(
        protected FluidStandaloneViewInterface&FluidStandaloneTemplateAwareViewInterface $view,
    ) {
    }

    public function assign(string $key, mixed $value): self
    {
        $this->view->assign($key, $value);

        return $this;
    }

    public function assignMultiple(array $values): self
    {
        $this->view->assignMultiple($values);

        return $this;
    }

    public function render(string $templateFileName = ''): string
    {
        $fileName = empty($templateFileName) ? 'Default.html.twig' : $templateFileName;

        $renderedView = $this->view->render($fileName);
        // @phpstan-ignore-next-line
        if ($renderedView !== null && !is_scalar($renderedView) && !$renderedView instanceof \Stringable) {
            throw new \RuntimeException('The rendered Fluid view can not be turned into string', 1731959329);
        }

        return (string) $renderedView;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->view->setRequest($request);
    }

    public function getRenderingContext(): RenderingContextInterface
    {
        if ($this->view instanceof FluidStandaloneAbstractTemplateView) {
            return $this->view->getRenderingContext();
        }
        // @phpstan-ignore-next-line
        throw new \RuntimeException('view must be an instance of ext:fluid \TYPO3Fluid\Fluid\View\AbstractTemplateView', 1721889095);
    }

    public function setRenderingContext(RenderingContextInterface $renderingContext): void
    {
        if ($this->view instanceof FluidStandaloneAbstractTemplateView) {
            $this->view->setRenderingContext($renderingContext);

            return;
        }
        // @phpstan-ignore-next-line
        throw new \RuntimeException('view must be an instance of ext:fluid \TYPO3Fluid\Fluid\View\AbstractTemplateView', 1721578954);
    }

    /**
     * @param array<mixed> $variables
     * @param bool         $ignoreUnknown
     */
    public function renderSection($sectionName, array $variables = [], $ignoreUnknown = false): mixed
    {
        trigger_error('renderSection is not supported by twig.');

        return '';
    }

    /**
     * @param array<mixed> $variables
     */
    public function renderPartial($partialName, $sectionName, array $variables, $ignoreUnknown = false): mixed
    {
        trigger_error('renderPartial is not supported by twig.');

        return '';
    }

    public function setTemplateName(string $templateName): void
    {
        $this->view->setTemplateName($templateName);
    }

    /**
     * @param array<mixed> $templateRootPaths
     */
    public function setTemplateRootPaths(array $templateRootPaths): void
    {
        $this->view->setTemplateRootPaths($templateRootPaths);
    }

    /**
     * @param array<mixed> $namespaces
     */
    public function setNamespaces(array $namespaces): void
    {
        $this->view->setNamespaces($namespaces);
    }
}
