export typo3DatabaseDriver=pdo_sqlite
export typo3DatabaseName=typo3

.PHONY: test
test: dependencies
	composer test

.PHONY: fix
fix: dependencies
	composer fix

.PHONY: dependencies
dependencies:
	composer install
