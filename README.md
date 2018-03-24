# Twig Extension for TYPO3

This TYPO3 extensions allows you to use the fabulous Twig template engine within your TYPO3 project.

You can use Twig templates in your Extbase controllers or in your TypoScript.

## Installation

This extension only works when installed in composer mode. If you are not familiar using composer together with TYPO3
yetyou can find a [how to on the TYPO3 website](https://composer.typo3.org/).

Install the extension with the following command:

```bash
composer require carl/typo3-twig
```

## Getting started

Rendering of Twig templates works nearly exactly the same way as rendering Fluid templates.

In contrast to Fluid there are no "layouts" nor "partials" in Twig. Everything is "just" a template. Therefore there
are some features missing that you are probably used by Fluid.

### Render templates in TypoScript

To render a Twig template you can use the `TWIGTEMPLATE` content object.

```typo3_typoscript
page.10 = TWIGTEMPLATE
page.10 {
    template = example.html.twig
    variables {
        foo = TEXT
        foo.value = Bar!
    }
    templateRootPaths {
        10 = EXT:twig/Resources/Private/TwigTemplates
    }
}
```

### Render templates in an Extbase controller

If you want to use twig from within your controller, you just have to switch the `$defaultViewObjectName` to 
`\Carl\Typo3\Twig\Extbase\Mvc\View\TwigView`. See the example below.

**⚠️ The support for Extbase is still in development. Therefore the path and filename of the templates have to be set 
manually. It is planned to have the same functionality as Fluid in future versions.** 

```php
<?php

class MyFantasticalController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    // setting the default view object class to TwigView will enable the Twig templates
    protected $defaultViewObjectName = \Carl\Typo3\Twig\Extbase\Mvc\View\TwigView::class;

    public function someAwesomeAction()
    {
        // since there is no full support for Extbase yet,
        // you have to configure the template root paths manually.
        $this->view->setTemplateName('template.html.twig');
        $this->view->setTemplateRootPaths(['EXT:twig/Resources/Private/TwigTemplates/']);
        
        // assign the variables as usual
        $this->view->assign('foo', 'BAZ!');
    }
}
```

## Alternatives

This extension is not the first extension that supports rendering Twig templates. We decided to create our own 
extension, because other extensions were either not maintained anymore, they carried to much overhead or they were not
developed close to the Fluid implementation.

* [T3twig](https://extensions.typo3.org/extension/t3twig/) (installs alternative MVC framework)
* [Twypo](https://extensions.typo3.org/extension/twypo/) (unmaintained)
* [Twig for TYPO3](https://extensions.typo3.org/extension/twig_for_typo3/) (unmaintained, only supports one template folder)

## Development Team

<table>
    <tr>
        <td align="center" valign="top">
            <img width="150" height="150" src="https://github.com/markuspoerschke.png?s=150">
            <br>
            <a href="https://github.com/markuspoerschke">Markus Poerschke</a>
            <p>Developer</p>
      </td>
    </tr>
</table>
