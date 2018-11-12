=======
Filters
=======

t3_html
=======

.. code-block:: twig

    {{ html | t3_html(parseFuncTSPath = 'lib.parseFunc_RTE') }}

Parses HTML that was created with an rich text editor.

arguments

html
    type: :code:`string`

parseFuncTSPath
    type: :code:`string`, default: :code:`lib.parseFunc_RTE`
