.. include:: ../../Includes.txt

====
Twig
====

Filters and functions that are provided by this extension are explained here.
Of course you can also use the build in filters, functions, tags, tests and operators.
You can find them in the `official reference documentation <https://twig.symfony.com/doc/2.x/#reference>`__.

Develop Custom Twig Extensions
==============================

Twig allows to `extend the template language <https://twig.symfony.com/doc/3.x/advanced.html>`__ by adding custom functions, filters, tags, and some more.
This TYPO3 extension currently only supports to add Twig Extensions (which include all mentioned).
Please read in the official Twig documentation, `how to create your own extension <https://twig.symfony.com/doc/3.x/advanced.html#creating-an-extension>`__.

To register the Twig Extension in your TYPO3 Extension, there is nothing more needed than enabling auto wiring and auto configuration in the `dependency injection service configuration <https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/DependencyInjection/Index.html#configure-dependency-injection-in-extensions>`__:

.. code-block:: yaml

    services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: false

    Vendor\Namespace\:
        resource: '../Classes/*'

All available Twig Extensions, that are covered by the above configuration, are automatically added to the Twig Environment.
