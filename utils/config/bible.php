<?php

/*
 * This file is part of the Laravel Bible package.
 *
 * (c) Prevail Ejimadu <prevailexcellent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /**
     * API Key From API.Bible Dashboard
     *
     */
    'apiKey' => getenv('BIBLE_API_KEY'),

    /**
     * API.Bible Live URL
     *
     */
    'url' => "https://api.scripture.api.bible/v1",

    /**
     * This is the list of available Versions
     *
     */
    'versions' => [
        [
            'name' => 'kjv', // King James Version
            'id' => 'de4e12af7f28f599-02',
        ],
        [
            'name' => 'igbo', // Open Igbo Contemporary Bible 2020
            'id' => 'a36fc06b086699f1-02',
        ],
        [
            'name' => 'rv', //Revised Standard Version
            'id' => '40072c4a5aba4022-01',
        ],
        [
            'name' => 'yoruba', // Open Yoruba Contemporary Bible 2020
            'id' => 'b8d1feac6e94bd74-01',
        ],
        [
            'name' => 'hausa', // Open Hausa Contemporary Bible 2020
            'id' => '0ab0c764d56a715d-01'
        ]
    ],

    /**
     * This is the default Version
     *
     */
    'default' => getenv('BIBLE_DEFAULT_VERSION'),

];
