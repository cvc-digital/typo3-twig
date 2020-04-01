.. include:: ../../Includes.txt

=======
Filters
=======

.. contents:: :local:
   :depth: 1

t3_html
=======

.. code-block:: twig

   {{ html | t3_html(parseFuncTSPath = 'lib.parseFunc_RTE') }}



Parses HTML that was created with an rich text editor.

Arguments
---------

.. rst-class:: dl-parameters

html
   :aspect:`Type:` :code:`string`

   The HTML that should be processed. Normally this is the content that is stored in the database.

parseFuncTSPath
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`'lib.parseFunc_RTE'`

   here you can define which setup should be used to transform the HTML content

t3_trans
========

.. code-block:: twig

   {{ key | t3_trans(arguments = [], extensionName = null) }}



Translates the given translation key into the active language.

Arguments
---------

.. rst-class:: dl-parameters

key
   :aspect:`Type:` :code:`string`

   the key for the translation

arguments
   :aspect:`Type:` :code:`array`
   :sep:`|` :aspect:`Default:` :code:`[]`

   the arguments that are replaced while translating

extensionName
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`null`

   the name of the TYPO3 extension where the translation file is located

t3_typolink
===========

.. code-block:: twig

   {{ linkText | t3_typolink(
       parameter,
       target = '',
       class = '',
       title = '',
       additionalParams = '',
       additionalAttributes = [],
       useCacheHash = false,
       addQueryString = false,
       addQueryStringMethod = 'GET',
       addQueryStringExclude = '',
       absolute = false
   ) }}



Creates a link from fields supported by the link wizard.

The :code:`linkText` will be wrapped within a link tag.

.. code-block:: twig

   {# Create links from simple text. #}
   {{ 'read_more' | t3_trans | t3_typolink('t3://record?identifier=123') }}


.. code-block:: twig

   {# Create links around complex HTML structures. #}
   {% filter t3_typolink('t3://record?identifier=123', target='_blank') %}
      <img src="read_more.png alt="read more"/>
   {% endfilter %}



Arguments
---------

.. rst-class:: dl-parameters

linkText
   :aspect:`Type:` :code:`string`

   the text that should be wrapped in an <a>-tag

parameter
   :aspect:`Type:` :code:`string`

   :code:`stdWrap.typolink` style parameter string.

target
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`''`

   Specifies where to open the linked document (e.g. :code:`_blank`).

class
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`''`

   class added to the :code:`<a>`-tag

title
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`''`

   Title attribute of the :code:`<a>`-tag. It can give more information to the user in form of a tooltip.

additionalParams
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`''`

   This is parameters that are added to the end of the URL. This must be code ready to insert after the last parameter.
   See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#additionalparams>`__

additionalAttributes
   :aspect:`Type:` :code:`array`
   :sep:`|` :aspect:`Default:` :code:`[]`

   additional HTML attributes that are added to the :code:`<a>`-tag

useCacheHash
   :aspect:`Type:` :code:`bool`
   :sep:`|` :aspect:`Default:` :code:`false`

   If set, the additionalParams list is exploded and calculated into a hash string appended to the URL, like "&cHash=ae83fd7s87".
   See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#usecachehash>`__

addQueryString
   :aspect:`Type:` :code:`bool`
   :sep:`|` :aspect:`Default:` :code:`false`

   Adds the query string to start of the link.
   See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#addquerystring>`__

addQueryStringMethod
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`'GET'`

   If set to GET or POST, then the parsed query arguments (GET or POST data) will be used.
   See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#addquerystring>`__

addQueryStringExclude
   :aspect:`Type:` :code:`string`
   :sep:`|` :aspect:`Default:` :code:`''`

   List of query arguments to exclude from the link. Typical examples are :code:`L` or :code:`cHash`.
   See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#addquerystring>`__

absolute
   :aspect:`Type:` :code:`bool`
   :sep:`|` :aspect:`Default:` :code:`false`

   Forces links to internal pages to be absolute, thus having a proper URL scheme and domain prepended.
   See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#forceabsoluteurl>`__
