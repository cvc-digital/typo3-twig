<?php

/*
 * Twig Extension for TYPO3 CMS
 * Copyright (C) 2018 Carl von Chiari GmbH
 *
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Twig Template Engine',
    'description' => 'Use the Twig template engine within your TYPO3 project. You can use Twig templates in TypoScript or together with Extbase.',
    'category' => 'fe',
    'author' => 'Markus Poerschke',
    'author_email' => 'poerschke@cvc.digital',
    'author_company' => 'Carl von Chiari GmbH',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.99.99',
            'extbase' => '8.7.0-9.99.99',
        ],
        'conflicts' => [
            't3twig' => '',
            'twig_for_typo3' => '',
        ],
    ],
];
