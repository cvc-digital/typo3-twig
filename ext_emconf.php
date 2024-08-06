<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2023 CARL von CHIARI GmbH
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
    'author' => 'CARL von CHIARI',
    'author_email' => 'opensource@cvc.digital',
    'author_company' => 'CARL von CHIARI GmbH',
    'state' => 'stable',
    'version' => '2.2.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.99.99',
        ],
    ],
];
