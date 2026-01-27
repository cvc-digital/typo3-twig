<?php

declare(strict_types=1);

namespace Cvc\Typo3\CvcTwig\View;

use Twig\Environment;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;

final readonly class TwigViewFactory implements ViewFactoryInterface
{
    public function __construct(
        private RenderingContextFactory $renderingContextFactory,
        private Environment $environment,
    ) {}

    public function create(ViewFactoryData $data): TwigViewAdapter
    {
        $pathTuple = [];
        if (!empty($data->templateRootPaths)) {
            $pathTuple['templateRootPaths'] = $data->templateRootPaths;
        }
        if (!empty($data->layoutRootPaths)) {
            $pathTuple['layoutRootPaths'] = $data->layoutRootPaths;
        }
        if (!empty($data->partialRootPaths)) {
            $pathTuple['partialRootPaths'] = $data->partialRootPaths;
        }

        $renderingContext = $this->renderingContextFactory->create($pathTuple, $data->request);
        if ($data->templatePathAndFilename) {
            $renderingContext->getTemplatePaths()->setTemplatePathAndFilename($data->templatePathAndFilename);
        }
        if ($data->format) {
            $renderingContext->getTemplatePaths()->setFormat($data->format);
        }
        $view = new TwigTemplateView($this->environment, $renderingContext);
        return new TwigViewAdapter($view);
    }
}
