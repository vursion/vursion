# VURSION
The missing monitor!

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vursion/vursion.svg?style=flat-square)](https://packagist.org/packages/vursion/vursion)
![Tests](https://github.com/vursion/vursion/workflows/tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/vursion/vursion.svg?style=flat-square)](https://packagist.org/packages/vursion/vursion)

## Installation

You can install the package via composer:

```bash
composer require vursion/vursion
```

***No need to register the service provider if you're using Laravel >= 5.5.
The package will automatically register itself.***
Once the package is installed, you can register the service provider in config/app.php in the providers array:
```
'providers' => [
	...
	Vursion\Vursion\VursionServiceProvider::class
],
```

Configure your vursion API key in your `.env` file.
```bash
VURSION_KEY=
```

To publish the config file to `config/vursion.php` run:
```bash
php artisan vursion:publish
```

This is the content of the published config file:

```php
return [

    'key' => env('VURSION_KEY'),

    'enabled' => env('VURSION_ENABLED', true),

];
```

### Don't forget to add the cron job needed to trigger Laravel’s scheduling!

Please see https://laravel.com/docs/master/scheduling for more information on adding the cron entry to your server.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email support@vursion.io instead of using the issue tracker.

## Credits

- [Jochen Sengier](https://github.com/celcius-jochen)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
