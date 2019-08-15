# Twig Extension for TYPO3

[![Build Status](https://travis-ci.org/cvc-digital/typo3-twig.svg?branch=master)](https://travis-ci.org/cvc-digital/typo3-twig)
[![GitHub license](https://img.shields.io/github/license/cvc-digital/typo3-twig.svg)](https://github.com/cvc-digital/typo3-twig/blob/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/cvc/typo3-twig.svg)](https://packagist.org/packages/cvc/typo3-twig)
[![TYPO3 Version](https://img.shields.io/badge/TYPO3-%5E8.7%20%7C%7C%20%5E9.5-orange.svg)](https://extensions.typo3.org/extension/cvc_twig/)

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
You can use it similar to the way, Fluid was used before.
The `variables` are rendered as content objects and data processing is also possible.

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

In the table below you can find competitive extensions that advertise to provide Twig integration:

|      | This extension | [Twig for TYPO3](https://extensions.typo3.org/extension/twig_for_typo3/) | [T3twig](https://extensions.typo3.org/extension/t3twig/) | [Twypo](https://extensions.typo3.org/extension/twypo/) |
|:----|:--------------:|:------------------------------------------------------------------------:|:--------------------------------------------------------:|:------------------------------------------------------:|
| Supports TYPO3 v8.7 LTS | ✅ | ✅ | ✅ | ❌ |
| Supports TYPO3 v9.5 LTS | ✅ | ❌ | ❌ | ❌ |
| Extbase support         | ✅ | ❌ | ❌ | ❌ |
| Build-in functions and filters | ✅ | ❌ | ✅ | ❌ |
| [Data processing](https://typo3worx.eu/2018/05/dataprocessing-fluid-templates/) | ✅ | ❌ | ❌ | ❌ |
| Installable via Composer | ✅ | ✅ | ✅ | ❌ |
| Build status | [![Build Status](https://travis-ci.org/cvc-digital/typo3-twig.svg?branch=master)](https://travis-ci.org/cvc-digital/typo3-twig) | [![Build Status](https://travis-ci.org/comwrap/typo3-twig_for_typo3.svg?branch=master)](https://travis-ci.org/comwrap/typo3-twig_for_typo3) | n/a | n/a |

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
