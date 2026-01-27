<?php

declare(strict_types=1);

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
    public ?string $templateName = null {
        set {
            $this->templateName = $value;
        }
    }
    public array $templateRootPaths = [] {
        set {
            $this->templateRootPaths = $value;
        }
    }
    public array $namespaces = [] {
        set {
            $this->namespaces = $value;
        }
    }

    public function setRequest(ServerRequestInterface $request): TwigTemplateView
    {
        $this->request = $request;
        return $this;
    }

    public function __construct(private readonly Environment $environment, ?RenderingContextInterface $context = null)
    {
        parent::__construct($context);
    }

    public function render($templateFileName = null): string
    {
        $templatePaths = $this->getRenderingContext()->getTemplatePaths()->getTemplateRootPaths();

        $fileSystemLoader = new FilesystemLoader($templatePaths);
        foreach ($this->namespaces as $namespace => $namespacedPaths) {
            $namespacedPaths = array_reverse($namespacedPaths);
            $namespacedPaths = array_map([GeneralUtility::class, 'getFileAbsFileName'], $namespacedPaths);
            // todo possibly use addPath instead of setPaths so the path already set by templatePaths doesnt get reset
            $fileSystemLoader->setPaths($namespacedPaths, $namespace);
        }
        $this->environment->setLoader($fileSystemLoader);

        $content = $this->environment->render($templateFileName, [
            'request' => $this->request,
            ...$this->getRenderingContext()->getVariableProvider()->getAll(),
        ]);
        $this->environment->setLoader($fileSystemLoader);

        return $content;
    }
}
