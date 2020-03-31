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
