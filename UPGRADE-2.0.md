# Upgrade from 1.x to 2.0

## Upgrading from TYPO3 v8 or v9

Version 2 of this extension is only compatible with TYPO3 v10 and PHP 7.4.
To get a smooth upgrade path, first upgrade your project to TYPO3 v9 using PHP 7.4.
Then upgrade TYPO3 to v10 and this extension to v2.

## Extending `StandaloneView` to add Twig Extensions

If the class `\Cvc\Typo3\CvcTwig\Mvc\View\StandaloneView` was extended to add custom extensions, [as described in this comment](https://github.com/cvc-digital/typo3-twig/issues/10#issuecomment-493046877), than the execution will fail, because the class `StandaloneView` is marked final now and the property `$extensions` was removed.

The old code might looked like this:

**ext_localconf.php:**

```php
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\Cvc\Typo3\CvcTwig\Mvc\View\StandaloneView::class] = [
    'className' => \MyProjec\MyExtension\Xclass\TwigStandAloneView::class,
];
```

**Xclass/TwigStandAlone.php:**

````php
class TwigStandAloneView extends \Cvc\Typo3\CvcTwig\Mvc\View\StandaloneView {

    public function __construct()
    {
        $this->extensions[] = MyTwigExtension::class;
    }
}
````

Removing the above code and enabling auto wiring will be enough to make `MyTwigExtension` available again.
Please read the chapter ["Develop Custom Twig Extensions"](https://docs.typo3.org/p/cvc/typo3-twig/master/en-us/Chapters/Twig/Index.html#develop-custom-twig-extensions) in the documentation.

## Extbase Controller Context

If you developed Twig Extensions that rely on the `\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext` object which was provided by the removed class `Cvc\Typo3\CvcTwig\Twig\Environment`,
then you can use the object of `\Cvc\Typo3\CvcTwig\Extbase\Mvc\ControllerContextStack`.
It will always contain the `ControllerContext` from the current request. If none is available, a context will be created.

**Before:**

```php
class MyExtension extends \Twig\Extension\AbstractExtension {
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('my_function', [$this, 'myFunction'], ['needs_environment' => true]),
        ];
    }

    public function myFunction(Cvc\Typo3\CvcTwig\Twig\Environment $environment /* ... */): string {
        $controllerContext = $environment->getControllerContext();

        // ...
    }
}
```

**After:**

```php
class MyExtension extends \Twig\Extension\AbstractExtension {
    private \Cvc\Typo3\CvcTwig\Extbase\Mvc\ControllerContextStack $controllerContextStack;

    public function __construct(\Cvc\Typo3\CvcTwig\Extbase\Mvc\ControllerContextStack $controllerContextStack) {
        $this->controllerContextStack = $controllerContextStack;
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('my_function', [$this, 'myFunction']),
        ];
    }

    public function myFunction(/* ... */): string {
        $controllerContext = $this->controllerContextStack->getControllerContext();

        // ...
    }
}
```
