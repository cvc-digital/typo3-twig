<?php

declare(strict_types=1);

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
        $renderedView = $this->view->render($this->templateName ?? $templateFileName ?? 'Default.html.twig');
        if ($renderedView !== null && !is_scalar($renderedView) && !$renderedView instanceof \Stringable) {
            throw new \RuntimeException('The rendered Fluid view can not be turned into string', 1731959329);
        }
        return (string)$renderedView;
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
        throw new \RuntimeException('view must be an instance of ext:fluid \TYPO3Fluid\Fluid\View\AbstractTemplateView', 1721889095);
    }

    public function setRenderingContext(RenderingContextInterface $renderingContext): void
    {
        if ($this->view instanceof FluidStandaloneAbstractTemplateView) {
            $this->view->setRenderingContext($renderingContext);
            return;
        }
        throw new \RuntimeException('view must be an instance of ext:fluid \TYPO3Fluid\Fluid\View\AbstractTemplateView', 1721578954);
    }

    public function renderSection($sectionName, array $variables = [], $ignoreUnknown = false): mixed
    {
        if ($this->view instanceof FluidStandaloneAbstractTemplateView) {
            return $this->view->renderSection($sectionName, $variables, $ignoreUnknown);
        }
        throw new \RuntimeException('view must be an instance of ext:fluid \TYPO3Fluid\Fluid\View\AbstractTemplateView', 1721746411);
    }

    public function renderPartial($partialName, $sectionName, array $variables, $ignoreUnknown = false): mixed
    {
        if ($this->view instanceof FluidStandaloneAbstractTemplateView) {
            return $this->view->renderPartial($partialName, $sectionName, $variables, $ignoreUnknown);
        }
        throw new \RuntimeException('view must be an instance of ext:fluid \TYPO3Fluid\Fluid\View\AbstractTemplateView', 1721746412);
    }

    public function setTemplateName(string $templateName): void
    {
        $this->view->templateName = $templateName;
    }

    public function setTemplateRootPaths(array $templateRootPaths): void
    {
        $this->view->templateRootPaths = $templateRootPaths;
    }

    public function setNamespaces(array $namespaces): void
    {
        $this->view->namespaces = $namespaces;
    }
}
