# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.0.0

### Changed

* Mark `Cvc\Typo3\CvcTwig\ContentObject\TwigTemplateContentObject` as internal
* Mark `Cvc\Typo3\CvcTwig\Mvc\View\StandaloneView` as final
* Mark `Cvc\Typo3\CvcTwig\Extbase\Mvc\View\TwigView` as final
* Mark Twig Extension classes as final

### Added

* Automatically resolve Twig extensions from service container
* Support for TYPO3 v10.4
* Support for PHP 7.4
* Twig Filter `t3_typolink` ([#5](https://github.com/cvc-digital/typo3-twig/pull/5))

### Removed

* Support for TYPO3 v9
* Support for TYPO3 v8
* Support for PHP 7.3
* Support for PHP 7.2
* Class `Cvc\Typo3\CvcTwig\Twig\Environment`
* Cache configuration `twig_timestamps` ([#31](https://github.com/cvc-digital/typo3-twig/pull/31))

## 1.1.2

### Fixed

* Release version (it was not included in `ext_emconf.php`)

## 1.1.1

### Fixed

* Link generating Twig functions ([#12](https://github.com/cvc-digital/typo3-twig/pull/12))

### Added

* Support PHP 7.4 ([#39](https://github.com/cvc-digital/typo3-twig/pull/39))

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
