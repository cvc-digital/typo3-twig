# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.0.x-dev

### Changed

* Mark `Cvc\Typo3\CvcTwig\ContentObject\TwigTemplateContentObject` as internal
* Mark `Cvc\Typo3\CvcTwig\Mvc\View\StandaloneView` as final
* Mark `Cvc\Typo3\CvcTwig\Extbase\Mvc\View\TwigView` as final

### Added

* Automatically resolve Twig extensions from service container
* Support for TYPO3 v10.3
* Support for PHP 7.4

### Removed

* Support for TYPO3 v9
* Support for TYPO3 v8
* Support for PHP 7.3
* Support for PHP 7.2
* Form related Twig function `t3_form_render` (install `cvc/typo3-twig-form` instead)
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
