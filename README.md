# Laravel Nginx

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mahdyaslami/laravel-nginx.svg?style=flat-square)](https://packagist.org/packages/mahdyaslami/laravel-nginx)
[![Total Downloads](https://img.shields.io/packagist/dt/mahdyaslami/laravel-nginx.svg?style=flat-square)](https://packagist.org/packages/mahdyaslami/laravel-nginx)
![GitHub Actions](https://github.com/mahdyaslami/laravel-nginx/actions/workflows/main.yml/badge.svg)

This package prepares the commands for creating Nginx config from the Blade format.

## Installation

You can install the package via composer:

```bash
composer require mahdyaslami/laravel-nginx
```

## Usage

Create `.nginx` directory and add `config.stub` file to it. this file contains
the Nginx configuration for your application. if you use a variable inside it
you should prepare it inside `.env` file with `NGINX_` prefix.

for example:

```
// .nginx/config.stub
server {
    root {{ $variable }};
}
```

```
// .env
NGINX_VARIABLE=/path/
```

You can use as many variables as you need. and use all blade directives. it will
be rendered by the blade.

For publish your config into `/etc/nginx/sites-available/` directory use
following command. and if you only want to see configuration after render use
`--show` option

```sh
$ php artisan nginx:publish
```

To create a symbolic link in the `/etc/nginx/sites-enabled` directory use the
following command.

```sh
$ php artisan nginx:link
```

Set the name of the file in the `sites-available` directory by evaluating the
`NGINX_FILENAME` variable in the `.env` directory.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Security

If you discover any security related issues, please email mahdyaslami@gmail.com
instead of using the issue tracker.

## Credits

-   [Mahdi Aslami Khavari](https://github.com/mahdyaslami)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

