===============
Getting started
===============

Rendering of Twig templates works nearly exactly the same way as rendering Fluid templates.

In contrast to Fluid there are no "layouts" nor "partials" in Twig. Everything is "just" a template. Therefore there
are some features missing that you are probably used by Fluid.

Render templates in TypoScript
==============================

To render a Twig template you can use the :code:`TWIGTEMPLATE` content object.

.. code:: typoscript

    page.10 = TWIGTEMPLATE
    page.10 {
        templateName = example.html.twig
        variables {
            foo = TEXT
            foo.value = Bar!
        }
        templateRootPaths {
            10 = EXT:twig/Resources/Private/TwigTemplates
        }
    }

Render templates in an Extbase controller
=========================================

If you want to use twig from within your controller, you just have to switch the :code:`$defaultViewObjectName` to
:code:`\Cvc\Typo3\CvcTwig\Extbase\Mvc\View\TwigView`. See the example below.

The template file will be automatically set to :code:`@controller/@action.@format.twig`.
The :code:`templateRootPaths` that are defined for extbase will be respected.
You should not forget to configure those using TypoScript.

Given you have the following controller; you just have to set the :code:`$defaultViewObjectName` to the TwigView class.

.. code-block:: php

    <?php

    // my_extension/Classes/Controller/MyFantasticalController.php

    class MyFantasticalController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
    {
        // setting the default view object class to TwigView will enable the Twig templates
        protected $defaultViewObjectName = \Cvc\Typo3\CvcTwig\Extbase\Mvc\View\TwigView::class;

        public function someAwesomeAction()
        {
            // assign the variables as usual
            $this->view->assign('foo', 'BAZ!');
        }
    }

The :code:`templateRootPaths` are configured using TypoScript:

.. code-block:: typoscript

    plugin.tx_my_extension {
        view {
            templateRootPaths.0 = EXT:my_extension/Resources/Private/Templates/
        }
    }

The Twig template has to be paced in :code:`my_extension/Resources/Private/Templates/`
and has to be named :code:`MyFantastical/someAwesome.html.twig`.

.. code-block:: twig

    {# my_extension/Resources/Private/Templates/MyFantastical/someAwesome.html.twig #}

    Foo: {{ foo }}
