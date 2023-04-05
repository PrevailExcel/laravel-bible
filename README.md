# laravel-bible

[![Latest Stable Version](https://poser.pugx.org/prevailexcel/laravel-bible/v/stable.svg)](https://packagist.org/packages/prevailexcel/laravel-bible)
[![License](https://poser.pugx.org/prevailexcel/laravel-bible/license.svg)](LICENSE.md)

> A Laravel Package for nearly 2500 Bible versions available across over 1600 languages, powered by API.Bible.

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Bible, simply require it

```bash
composer require prevailexcel/laravel-bible
```

Or add the following line to the require block of your `composer.json` file.

```
"prevailexcel/laravel-bible": "1.0.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.



Once Laravel Bible is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

```php
'providers' => [
    ...
    PrevailExcel\Bible\BibleServiceProvider::class,
    ...
]
```

> If you use **Laravel >= 5.5** you can skip this step and go to [**`configuration`**](https://github.com/PrevailExcel/laravel-bible#configuration)

* `PrevailExcel\Bible\BibleServiceProvider::class`

Also, register the Facade like so:

```php
'aliases' => [
    ...
    'Bible' => PrevailExcel\Bible\Facades\Bible::class,
    ...
]
```

## Configuration

You can publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="PrevailExcel\Bible\BibleServiceProvider"
```

A configuration-file named `bible.php` with some sensible defaults will be placed in your `config` directory:

```php
<?php


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

```
## Usage

Open your `.env` file and add your api key, and the default version you want to use like so:

```php
BIBLE_API_KEY="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
BIBLE_DEFAULT_VERSION="kjv"
```
>You have to register or login to your [API.Bible](https://scripture.api.bible) account and get your app api key.
##
*The default version must be set in your `config/bible.php` first.
*If you are using a hosting service like heroku, ensure to add the above details to your configuration variables.*

### You can now call it the `bible()` helper function or the `Bible::method()` facade.


```php
<?php

use Illuminate\Support\Facades\Route;
use PrevailExcel\Bible\Facades\Bible;

Route::get('/bible-verse', function () {

    request()->book = 'JHN';  
    request()->chapter = 3;  
    request()->verse = 16;      // You can set requests from your form
    return Bible::verse();  

   // or simply

    return Bible::verse(16, 3, 'JHN');    // You can pass to method directly
});

Route::get('/bible-search', function () {
    request()->term = 'King solomon';
    return bible()->search();   // You can also use the bible() helper.
});

```

Some other fluent methods this package provides are listed here.
```php

/**
 * This is the method to get the full list of availble bibles, you can copy the id of the version you want, alonside and name you want to call it and add it to your config/bible.php.
 * @returns array
 */
 Bible::bibles();
/**
 * Alternatively, use the helper.
 */
bible()->bibles();

/**
 * This is the method to get a particular bible version.
 * Leave empty to use the default version
 * @param string|null $version
 * @return array
 */
 Bible::bible();
 
/**
 * Alternatively, use the helper.
 */
bible()->bible('igbo');


/**
 * This is the method to get list of books in a particular bible version.
 * Leave empty to use the default version
 * @param string|null $version
 * @return array
 */
 Bible::books('kjv');
 
/**
 * Alternatively, use the helper.
 */
bible()->books();


/**
 * This is the method to get a book from the bible.
 * @param string $book
 * @return array
 */
 Bible::book('GEN');
 
/**
 * Alternatively, use the helper.
 */
bible()->book('MAT');

/**
 * This is the method to get a chapter from a book from the bible.
 * @param string $chapter 
 * @param string $book
 * @return array
 */
 Bible::chapter(23, 'LUK');
 
/**
 * Alternatively, use the helper.
 */
bible()->chapter(5, 'MAT');

/**
 * This is the method to get full list of verses of a chapter from a book from the bible.
 * @param string $chapter 
 * @param string $book
 * @return array
 */
 Bible::verses(23, 'LUK');
 
/**
 * Alternatively, use the helper.
 */
bible()->verses(5, 'MAT');

```

## Todo

* Add Comprehensive Tests
* Add support for Audio bibles
* Add Bible Sections

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

## How can I thank you?

Why not star the github repo? I'd love the attention! Why not share the link for this repository on Twitter or HackerNews? Spread the word!

Don't forget to [follow me on twitter](https://twitter.com/EjimaduPrevail)!
Also check out my page on medium to catch articles and tutorials on Laravel [follow me on medium](https://medium.com/@prevailexcellent)!

Thanks!
Chimeremeze Prevail Ejimadu.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
