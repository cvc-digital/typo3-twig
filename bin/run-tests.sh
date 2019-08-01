#!/usr/bin/env bash

export typo3DatabaseDriver="pdo_sqlite"
export typo3DatabaseName="typo3"
php .Build/bin/phpunit -c .Build/vendor/typo3/testing-framework/Resources/Core/Build/FunctionalTests.xml Tests/Functional
