.. include:: ../../Includes.txt

=========
Functions
=========

.. contents:: :local:
   :depth: 1

dump
====

.. code-block:: twig
   {{ dump(vars) }}


Displays the content of the variable(s) on the screen.

The :code:`dump()` functions prints the contents of a variable.
This is useful for debugging.
Please ensure that the frontend debug mode is on, because otherwise the function does not print anything.
Internally :code:`DebuggerUtility::var_dump()` is used.

.. code-block:: twig
   {# print a single variable #}
   {{ dump(foo) }}
   
   {# print multiple variables #}
   {{ dump(foo, bar, baz) }}
   
   {# print all variables #}
   {{ dump() }}

Arguments
---------

.. rst-class:: dl-parameters

vars
   :aspect:`Type:` :code:`mixed`

   Any number of variables.

t3_cobject
==========

.. code-block:: twig
   {{ t3_cobject(
       typoScriptObjectPath,
       data = null,
       currentValueKey = null,
       table = null
   ) }}


Renders a TypoScript object. The content object renderer can be populated using the data argument.


Arguments
---------

.. rst-class:: dl-parameters

typoScriptObjectPath
   :aspect:`Type:` :code:`string`

data
   :aspect:`Type:` :code:`mixed`
   :sep:`|` :aspect:`Default:` :code:`null`

currentValueKey
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`null`

table
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`null`

t3_uri_image
============

.. code-block:: twig
   {{ t3_uri_image(
       src = null,
       treatIdAsReference = false,
       image = null,
       crop = null,
       cropVariant = 'default',
       width = '',
       height = '',
       minWidth = 0,
       minHeight = 0,
       maxWidth = 0,
       maxHeight = 0,
       absolute = false
   ) }}


Returns the URL to the given image. If an error occurs, then :code:`null` is returned and no error is raised.


Arguments
---------

.. rst-class:: dl-parameters

src
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`null`

treatIdAsReference
   :aspect:`Type:` :code:`bool`
   :sep:`|` :aspect:`Default:` :code:`false`

image
   :aspect:`Type:` :code:`mixed`
   :sep:`|` :aspect:`Default:` :code:`null`

   the image can be a reference or an instance of :code:`FileInterface` or :code:`FileReference`

crop
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`null`

   the JSON-formatted crop settings

cropVariant
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`'default'`

width
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`''`

height
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`''`

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

absolute
   :aspect:`Type:` :code:`bool`
   :sep:`|` :aspect:`Default:` :code:`false`

t3_uri_page
===========

.. code-block:: twig
   {{ t3_uri_page(
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



Arguments
---------

.. rst-class:: dl-parameters

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
   :sep:`|` :aspect:`Default:` :code:`''`

linkAccessRestrictedPages
   :aspect:`Type:` :code:`bool`
   :sep:`|` :aspect:`Default:` :code:`false`

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
   :sep:`|` :aspect:`Default:` :code:`''`

t3_uri_record
=============

.. code-block:: twig
   {{ t3_uri_record(table, recordUid) }}


Generates a link for the given record.

A `link handler &lt;https://docs.typo3.org/typo3cms/extensions/core/latest/Changelog/8.6/Feature-79626-IntegrateRecordLinkHandler.html&gt;`__ must be configured for the mapped table.


Arguments
---------

.. rst-class:: dl-parameters

table
   :aspect:`Type:` :code:`string`

   the table of the record

recordUid
   :aspect:`Type:` :code:`int`

   the UID of the record

t3_uri_typolink
===============

.. code-block:: twig
   {{ t3_uri_typolink(parameter, additionalParams = []) }}



Arguments
---------

.. rst-class:: dl-parameters

parameter
   :aspect:`Type:` :code:`string`

additionalParams
   :aspect:`Type:` :code:`array`
   :sep:`|` :aspect:`Default:` :code:`[]`
