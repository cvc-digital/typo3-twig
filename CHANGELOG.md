# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.0.x-dev

### Changed

* Mark `TwigTemplateContentObject` as internal

### Added

* Automatically resolve Twig extensions from service container
* Support for TYPO3 v10.0

### Removed

* Support for TYPO3 v9
* Support for TYPO3 v8
* Form related Twig function `t3_form_render` (install `cvc/typo3-twig-form` instead)
* Extbase related (install `cvc/typo3-twig-extbase` instead)
    * Twig functions
        * `t3_uri_model`
        * `t3_uri_action`
    * Class `Cvc\Typo3\CvcTwig\Extbase\Mvc\View\TwigView`
* Class `Cvc\Typo3\CvcTwig\Twig\Environment`
* Cache configuration `twig_timestamps` ([#31](https://github.com/cvc-digital/typo3-twig/pull/31))

## 1.1.0

### Added

* Namespaced template paths ([#9](https://github.com/cvc-digital/typo3-twig/pull/9))
* Documentation for the TWIGTEMPLATE content object ([#7](https://github.com/cvc-digital/typo3-twig/pull/7))

## 1.0.1

### Added
* Support PHP 7.3

## 1.0.0

### Added
* Initial release of the twig extension for TYPO3
