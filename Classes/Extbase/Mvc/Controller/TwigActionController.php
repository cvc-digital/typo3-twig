<?php

declare(strict_types=1);

namespace Cvc\Typo3\CvcTwig\Extbase\Mvc\Controller;

use Cvc\Typo3\CvcTwig\Extbase\Mvc\View\TwigView;
use Cvc\Typo3\CvcTwig\View\TwigViewFactory;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Fluid\Fluid\View\ViewInterface as FluidStandaloneViewInterface;

class TwigActionController extends ActionController
{
    protected ?string $defaultViewObjectName = TwigView::class;

    public function __construct(
        private readonly TwigViewFactory $twigViewFactory,
    ) {
    }

    protected function resolveView(): FluidStandaloneViewInterface|ViewInterface
    {
        if ($this->defaultViewObjectName === TwigView::class) {
            $configuration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $extensionKey = $this->request->getControllerExtensionKey();
            $templateRootPaths = $this->addDefaultPathToPaths($configuration['view']['templateRootPaths'] ?? [], 'EXT:'.$extensionKey.'/Resources/Private/TwigTemplates/');
            $viewFactoryData = new ViewFactoryData(
                templateRootPaths: $templateRootPaths,
                partialRootPaths: null,
                layoutRootPaths: null,
                request: $this->request,
                format: $this->request->getFormat(),
            );
            $view = $this->twigViewFactory->create($viewFactoryData);
            $view->setNamespaces($configuration['view']['namespaces'] ?? []);

            $renderingContext = $view->getRenderingContext();
            $renderingContext->setControllerName($this->request->getControllerName());
            $renderingContext->setControllerAction(ucfirst($this->request->getControllerActionName()));

            $view->assign('settings', $this->settings);

            return $view;
        }

        return parent::resolveView();
    }
}
