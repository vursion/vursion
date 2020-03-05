# vursion

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vursion/vursion.svg?style=flat-square)](https://packagist.org/packages/vursion/vursion)
[![Total Downloads](https://img.shields.io/packagist/dt/vursion/vursion.svg?style=flat-square)](https://packagist.org/packages/vursion/vursion)

## Installation

You can install the package via composer:

```bash
composer require vursion/vursion
```

The package will automatically register itself.

To publish the config file to `config/vursion.php` run:
```bash
php artisan vendor:publish --provider="Vursion\Vursion\VursionServiceProvider"
```

**Don't forget to add the cron job needed to trigger Laravel’s scheduling!**
Please see https://laravel.com/docs/master/scheduling for more information on adding the Cron entry to your server.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Security

If you discover any security related issues, please email jochen@celcius.be instead of using the issue tracker.

## Credits

- [Jochen Sengier](https://github.com/celcius-jochen)
