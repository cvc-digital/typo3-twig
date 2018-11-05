# Twig Extension for TYPO3</h1>

This TYPO3 extensions allows you to use the fabulous Twig template engine within your TYPO3 project.

You can use Twig templates in your Extbase controllers or in your TypoScript.

## Installation

This extension only works when installed in composer mode. If you are not familiar using composer together with TYPO3
yetyou can find a [how to on the TYPO3 website](https://composer.typo3.org/).

Install the extension with the following command:

```bash
composer require cvc/typo3-twig
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
    templateName = example.html.twig
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
`\Cvc\Typo3\CvcTwig\Extbase\Mvc\View\TwigView`. See the example below.

The template file will be automatically set to `@controller/@action.@format.twig`.
The `templateRootPaths` that are defined for extbase will be respected.
You should not forget to configure those using TypoScript.

Given you have the following controller; you just have to set the `$defaultViewObjectName` to the TwigView class.

```php
<?php

// my_extension/Classes/Controller/MyFantasticalController.php

class MyFantasticalController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    // setting the default view object class to TwigView will enable the Twig templates
    protected $defaultViewObjectName = \Cvc\Typo3\CvcTwig\Extbase\Mvc\View\TwigView::class;

    public function someAwesomeAction()
    {
        // assign the variables as usual
        $this->view->assign('foo', 'BAZ!');
    }
}
```

The `templateRootPaths` are configured using TypoScript:

```typo3_typoscript
plugin.tx_my_extension {
    view {
        templateRootPaths.0 = EXT:my_extension/Resources/Private/Templates/
    }
}
```

The Twig template has to be paced in `my_extension/Resources/Private/Templates/`
and has to be named `MyFantastical/someAwesome.html.twig`.

```twig
{# my_extension/Resources/Private/Templates/MyFantastical/someAwesome.html.twig #}

Foo: {{ foo }}
```

## Documentation

Filters and functions that are provided by this extension are explained here.
Of course you can also use the build in filters, functions, tags, tests and operators.
You can find them in the [official reference documentation](https://twig.symfony.com/doc/2.x/#reference).

### Functions

#### dump

```twig
{{ dump(var1, var2, ...) }}
```

The `dump()` functions prints the contents of a variable. This is useful for debugging. Please ensure that your frontend
is in debug mode because otherwise the function does not print anything. Internally `DebuggerUtility::var_dump()` is used.

```twig
{# print a single variable #}
{{ dump(foo) }}

{# print multiple variables #}
{{ dump(foo, bar, baz) }}

{# print all variables #}
{{ dump() }}
```

### Filters

#### t3_html

```twig
{{ html | t3_html(parseFuncTSPath = 'lib.parseFunc_RTE') }}
```

`html`
: **type**: `string`

`parseFuncTSPath`
: **type**: `string`, **default**: `lib.parseFunc_RTE`

Parses HTML that was created with an rich text editor.

## Alternatives

This extension is not the first extension that supports rendering Twig templates. We decided to create our own
extension, because other extensions were either not maintained anymore, they carried to much overhead or they were not
developed close to the Fluid implementation.

| Extension Name                                                           | 8.7 LTS | 9.5 LTS | Extbase Support | Install via Extension Manager | Installation via Composer | Comment                                     |
|:-------------------------------------------------------------------------|:-------:|:-------:|:---------------:|:-----------------------------:|:-------------------------:|:--------------------------------------------|
| This extension                                                           |    ✓    |    ✓    |        ✓        |               -               |             ✓             | -                                           |
| [Twig for TYPO3](https://extensions.typo3.org/extension/twig_for_typo3/) |    ✓    |    -    |        -        |               -               |             ✓             | supports only single template folder        |
| [T3twig](https://extensions.typo3.org/extension/t3twig/)                 |    ✓    |    -    |        -        |               -               |             ✓             | requires alternative non-core MVC framework |
| [Twypo](https://extensions.typo3.org/extension/twypo/)                   |    -    |    -    |        -        |               ✓               |             -             | unmaintained                                |

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
