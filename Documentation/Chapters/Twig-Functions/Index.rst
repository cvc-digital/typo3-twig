.. include:: ../../Includes.txt

=========
Functions
=========

.. contents:: :local:
   :depth: 1

dump
====

.. code-block:: twig

    {{ dump(var1, var2, ...) }}

The :code:`dump()` functions prints the contents of a variable.
This is useful for debugging.
Please ensure that your frontend is in debug mode because otherwise the function does not print anything.
Internally :code:`DebuggerUtility::var_dump()` is used.

.. code-block:: twig

    {# print a single variable #}
    {{ dump(foo) }}

    {# print multiple variables #}
    {{ dump(foo, bar, baz) }}

    {# print all variables #}
    {{ dump() }}

t3_cobject
==========

.. code-block:: twig

    {{ t3_cobject(typoScriptObjectPath, data = null, currentValueKey = null, table = null) }}

Renders a TypoScript object.
The content object renderer can be populated using the :code:`data` argument.

Arguments
---------

.. rst-class:: dl-parameters

typoScriptObjectPath
    :aspect:`Type:` :code:`string`

data
    :aspect:`Type:` :code:`mixed`
    :sep:`|` :aspect:`Default:` :code:`null`
currentValueKey
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`null`

table
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`null`

t3_form_render
==============

.. code-block:: twig

    {{ t3_form_render(persistenceIdentifier = null, factoryClass = 'ArrayFormFactory', prototypeName = null, overrideConfiguration = {}) }}

Renders a form using the `form framework <https://docs.typo3.org/typo3cms/extensions/form/Index.html>`__.

Arguments
---------

.. rst-class:: dl-parameters

persistenceIdentifier
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`null`

    The identifier of the form, if a YAML file is used.
    If :code:`null`, then a Factory class needs to be set.

factoryClass
    :aspect:`Type:` :code:`string`
    :sep:`|` :aspect:`Default:` :code:`\TYPO3\CMS\Form\Domain\Factory\ArrayFormFactory`

    The fully qualified class name of the factory.

prototypeName
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`?string`

    Name of the prototype to use.

overrideConfiguration
    :aspect:`Type:`  :code:`array`
    :sep:`|` :aspect:`Default:` :code:`[]`

    Factory specific configuration.
    This will allow to add additional configuration related to the current view.

t3_uri_action
=============

.. code-block:: twig

    {{ t3_uri_action(
        action,
        arguments = [],
        controller = null,
        extensionName = null,
        pluginName = null,
        pageUid = null,
        pageType = 0,
        noCache = false,
        section = '',
        format = '',
        linkAccessRestrictedPages = false,
        additionalParams = [],
        absolute = false,
        addQueryString = false,
        argumentsToBeExcludedFromQueryString = [],
        addQueryStringMethod = ''
    ) }}

Renders an URI to an Extbase action.

Arguments
---------

.. rst-class:: dl-parameters


action
    :aspect:`Type:` :code:`string`

arguments
    :aspect:`Type:` :code:`array`
    :sep:`|` :aspect:`Default:` :code:`[]`

controller
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`null`

extensionName
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`null`

pluginName = null,
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`null`

pageUid,
    :aspect:`Type:` :code:`?int`
    :sep:`|` :aspect:`Default:` :code:`null`

pageType
    :aspect:`Type:` :code:`int`
    :sep:`|` :aspect:`Default:` :code:`0`

noCache
    :aspect:`Type:` :code:`bool`
    :sep:`|` :aspect:`Default:` :code:`false`

section = '',
    :aspect:`Type:` :code:`string`
    :sep:`|` :aspect:`Default:` :code:`""` (empty string)

format,
    :aspect:`Type:` :code:`string`
    :sep:`|` :aspect:`Default:` :code:`""` (empty string)

linkAccessRestrictedPages
    :aspect:`Type:` :code:`bool`
    :sep:`|` :aspect:`Default:` :code:`false`

additionalParams
    :aspect:`Type:` :code:`array`
    :sep:`|` :aspect:`Default:` :code:`[]`

absolute
    :aspect:`Type:` :code:`bool`
    :sep:`|` :aspect:`Default:` :code:`false`

addQueryString
    :aspect:`Type:` :code:`bool`
    :sep:`|` :aspect:`Default:` :code:`false`

argumentsToBeExcludedFromQueryString
    :aspect:`Type:` :code:`array`
    :sep:`|` :aspect:`Default:` :code:`[]`

addQueryStringMethod
    :aspect:`Type:` :code:`string`
    :sep:`|` :aspect:`Default:` :code:`""` (empty string)

t3_uri_image
============

.. code-block:: twig

    {{ t3_uri_image(
        src = null,
        treatIdAsReference = false,
        image = null,
        crop = false,
        cropVariant = 'default',
        width = '',
        height = '',
        minWidth = 0,
        minHeight = 0,
        maxWidth = 0,
        maxHeight = 0,
        absolute = false
    ) }}

Returns the URL to the given image.
If an error occurs, then :code:`null` is returned and no error is raised.

Arguments
---------

.. rst-class:: dl-parameters

src
    :aspect:`Type:` :code:`?string`

treatIdAsReference
    :aspect:`Type:` :code:`bool`
    :sep:`|` :aspect:`Default:` :code:`false`

image
    :aspect:`Type:` :code:`?mixed`

    The image can be a reference or an instance of :code:`FileInterface` or :code:`FileReference`.

crop
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`null`

    The JSON-formatted crop settings.

cropVariant
    :aspect:`Type:` :code:`string`
    :sep:`|` :aspect:`Default:` :code:`default`

width
    :aspect:`Type:` :code:`int`
    :sep:`|` :aspect:`Default:` :code:`0`

height
    :aspect:`Type:` :code:`int`
    :sep:`|` :aspect:`Default:` :code:`0`

minWidth
    :aspect:`Type:` :code:`int`
    :sep:`|` :aspect:`Default:` :code:`0`

minHeight
    :aspect:`Type:` :code:`int`
    :sep:`|` :aspect:`Default:` :code:`0`

maxWidth
    :aspect:`Type:` :code:`int`
    :sep:`|` :aspect:`Default:` :code:`0`

maxHeight
    :aspect:`Type:` :code:`int`
    :sep:`|` :aspect:`Default:` :code:`0`

t3_uri_page
===========

.. code-block:: twig

    {{ t3_uri_page(
        pageUid = null,
        additionalParams = [],
        pageType = 0,
        noCache = false,
        section = '',
        linkAccessRestrictedPages = false,
        absolute = false,
        addQueryString = false,
        argumentsToBeExcludedFromQueryString = [],
        addQueryStringMethod = ''
    ) }}

.. rst-class:: dl-parameters

pageUid
    :aspect:`Type:` :code:`?int`
    :sep:`|` :aspect:`Default:` :code:`null`

additionalParams
    :aspect:`Type:` :code:`array`
    :sep:`|` :aspect:`Default:` :code:`[]`

pageType
    :aspect:`Type:` :code:`int`
    :sep:`|` :aspect:`Default:` :code:`0`

noCache
    :aspect:`Type:` :code:`bool`
    :sep:`|` :aspect:`Default:` :code:`false`

section
    :aspect:`Type:` :code:`string`
    :sep:`|` :aspect:`Default:` :code:`""` (empty string)

linkAccessRestrictedPages
    :aspect:`Type:` :code:`bool`
    :sep:`|` :aspect:`Default:` :code:`false`

absolute
    :aspect:`Type:` :code:`bool`
    :sep:`|` :aspect:`Default:` :code:`false`

addQueryString
    :aspect:`Type:` :code:`bool`

argumentsToBeExcludedFromQueryString
    :aspect:`Type:` :code:`array`
    :sep:`|` :aspect:`Default:` :code:`[]`

addQueryStringMethod
    :aspect:`Type:` :code:`?string`
    :sep:`|` :aspect:`Default:` :code:`""` (empty string)

t3_uri_record
=============

.. code-block:: twig

    {{ t3_uri_record(table, recordUid) }}

Generates a link for the given record.
A `link handler <https://docs.typo3.org/typo3cms/extensions/core/latest/Changelog/8.6/Feature-79626-IntegrateRecordLinkHandler.html>`__ must be configured for the mapped table.

Arguments
---------

.. rst-class:: dl-parameters

table
    :aspect:`Type:` :code:`string`

    The table of the record.

recordUid
    :aspect:`Type:` :code:`int`

    The uid of the record.

t3_uri_typolink
===============

.. code-block:: twig

    {{ t3_uri_typolink(parameter, additionalParams = {})

Arguments
---------

parameter
    :aspect:`Type:` :code:`string`

additionalParams
    :aspect:`Type:` :code:`array`
    :sep:`|` :aspect:`Default:` :code:`[]`
