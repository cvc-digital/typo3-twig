.. include:: ../../Includes.txt

============
TWIGTEMPLATE
============

With this Content Object a template file (e.g. a HTML file) is rendered.
The functionality is close to `FLUIDTEMPLATE <https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Fluidtemplate/Index.html>`__.

Properties
==========

.. rst-class:: dl-parameters

templateName
    :aspect:`Type` :code:`string` / :code:`stdWrap`

    The template that is used for rendering.

templateRootPaths
    :aspect:`Type` :code:`string` / :code:`stdWrap`

    The root directories for the loader to look in.

variables
    :aspect:`Type` :code:`cObject[]`

    Sets variables that should be available in the Twig template. The keys are the variable names.

    Reserved variable names are :code:`data` and :code:`current`, which are filled automatically with the current data set.

settings
    :aspect:`Type` :code:`array`

    Sets the given settings array in the Twig template.

dataProcessing
    :aspect:`Type` :code:`array`

    Add one or multiple processors to manipulate the variables that will be passed to the template engine.
    The sub- property options can be used to pass parameters to the processor class.

    **Example:**

    .. code-block:: typoscript

        page.10 = TWIGTEMPLATE
        page.10 {
            templateName = EXT:site_package/Resources/Private/TwigTemplates/template.html.twig
            dataProcessing {
                1 = Vendor\YourExtensionKey\DataProcessing\MyFirstCustomProcessor
                2 = Vendor2\AnotherExtensionKey\DataProcessing\MySecondCustomProcessor
                2.options {
                    myOption = SomeValue
                }
            }
        }

    The build-in data processors are documented `here <https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Fluidtemplate/Index.html#dataprocessing>`__.
