export typo3DatabaseDriver=pdo_sqlite
export typo3DatabaseName=typo3
export XDEBUG_MODE=coverage

.PHONY: test
test: test-phpunit test-code-style test-composer-normalize test-phpstan

.PHONY: test-phpunit
test-phpunit: dependencies
	composer exec -v phpunit -- ${PHPUNIT_OPTIONS}

.PHONY: test-code-style
test-code-style: dependencies
	composer exec -v php-cs-fixer -- fix --dry-run --diff

.PHONY: test-composer-normalize
test-composer-normalize: dependencies
	composer normalize --dry-run

.PHONY: test-phpstan
test-phpstan: dependencies
	composer exec -v phpstan -- analyse

.PHONY: fix
fix: fix-code-style fix-composer-normalize

.PHONY: fix-code-style
fix-code-style: dependencies
	composer exec -v php-cs-fixer -- fix

.PHONY: fix-composer-normalize
fix-composer-normalize: dependencies
	composer normalize

.PHONY: dependencies
dependencies:
	composer install

.PHONY: documentation
documentation: dependencies
	php bin/generate-docs.php

.PHONY: clean
clean:
	rm -rf .Build composer.lock
