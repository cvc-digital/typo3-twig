#!/usr/bin/env bash

export typo3DatabaseDriver="pdo_sqlite"
export typo3DatabaseName="typo3"
php .Build/bin/phpunit -c ./phpunit.xml --coverage-text
