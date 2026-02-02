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
    private ?string $templateName = null;
    private array $templateRootPaths = [];
    private array $namespaces = [];

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
            $fileSystemLoader->setPaths($namespacedPaths, $namespace);
        }
        $this->environment->setLoader($fileSystemLoader);

        return $this->environment->render($templateFileName, [
            'request' => $this->request,
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

    public function setTemplateRootPaths(array $templateRootPaths): void
    {
        $this->templateRootPaths = $templateRootPaths;
    }

    public function addTemplateRootPath(string $templateRootPath): void
    {
        $this->templateRootPaths[] = $templateRootPath;
    }

    public function getTemplateRootPaths(): array
    {
        return $this->templateRootPaths;
    }

    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    public function setNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;
    }
}
