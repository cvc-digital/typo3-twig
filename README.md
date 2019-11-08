# Twig Extension for TYPO3

[![Build Status](https://travis-ci.org/cvc-digital/typo3-twig.svg?branch=master)](https://travis-ci.org/cvc-digital/typo3-twig)
[![GitHub license](https://img.shields.io/github/license/cvc-digital/typo3-twig.svg)](https://github.com/cvc-digital/typo3-twig/blob/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/cvc/typo3-twig.svg)](https://packagist.org/packages/cvc/typo3-twig)
[![TYPO3 Version](https://img.shields.io/badge/TYPO3-%5E10.0-orange.svg)](https://extensions.typo3.org/extension/cvc_twig/)
[![codecov](https://codecov.io/gh/cvc-digital/typo3-twig/branch/master/graph/badge.svg)](https://codecov.io/gh/cvc-digital/typo3-twig)

This TYPO3 extensions allows you to use the fabulous Twig template engine within your TYPO3 project.

You can use Twig templates in your Extbase controllers or in your TypoScript.

## Version compatibility

The following table shows which versions of this package are compatible with which TYPO3 version.
Version 1 is compatible to TYPO3 v8.7 and v9.5.
Version 2 that is developed on the `master` branch is compatible to TYPO3 v10.
Since Version 2, PHP `7.2` or above is required.

|           | 1.x  |    2.x   |
|-----------|:----:|:--------:|
| TYPO3 v10 |  ❌  |    ✅    |
| TYPO3 v9  |  ✅  |    ❌    |
| TYPO3 v8  |  ✅  |    ❌    |
| PHP 7.3   |  ✅  |    ✅    |
| PHP 7.2   |  ✅  |    ✅    |

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
You can use it similar to the way, Fluid was used before.
The `variables` are rendered as content objects and data processing is also possible.

```typo3_typoscript
page = PAGE
page.10 = TWIGTEMPLATE
page.10 {
    templateName = example.html.twig
    variables {
        foo = TEXT
        foo.value = Bar!
    }
    templateRootPaths {
        10 = EXT:cvc_twig/Resources/Private/TwigTemplates
    }
    dataProcessing {
        10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
        10 {
            references.fieldName = image
        }
    }
}
```

## Documentation

The full documentation is available here: [cvc_twig Documentation](https://docs.typo3.org/p/cvc/typo3-twig/master/en-us/).

## Alternatives

This extension is not the first extension that supports rendering Twig templates. We decided to create our own
extension, because other extensions were either not maintained anymore, they carried to much overhead or they were not
developed close to the Fluid reference implementation.

In the table below you can find other extensions that provides an integration for the Twig template engine:

* [Twig for TYPO3](https://extensions.typo3.org/extension/twig_for_typo3/)
* [T3twig](https://extensions.typo3.org/extension/t3twig/)
* [Twypo](https://extensions.typo3.org/extension/twypo/)

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
