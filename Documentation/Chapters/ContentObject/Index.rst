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

namespaces
    :aspect:`Type` :code:`array`

    The file loader shipped with Twig allows to register namespaced folders.
    A namespace can be referenced with :code:`@namespace` followed by the path to the template.

    **Example:**

    .. code-block:: typoscript

        page.10 = TWIGTEMPLATE
        page.10 {
            templateName = @myComponents/text_and_media.html.twig
            namespaces {
                myComponents {
                    10 = EXT:example_site/Private/frontend/src/components
                }
            }
        }

    The given code example defines a namespace called :code:`myComponents`.
    A template that is located in :code:`EXT:example_site/Private/frontend/src/components` can be referenced using the namespace.
    The template name will looks like :code:`@myComponents/text_and_media.html.twig`.
