=========
Functions
=========

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
