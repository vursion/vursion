# Changelog

All notable changes to `vursion` will be documented in this file

## 1.5.0 - 2023-02-15
- Support Laravel 10

## 1.4.0 - 2022-12-09
- Support PHP 8.2

## 1.3.0 - 2022-02-10
- Support Laravel 9

## 1.2.0 - 2021-12-22
- Support PHP 8.1

## 1.1.0 - 2021-04-26
- Send data of `package.json` and `package.lock` to the API.

## 1.0.1 - 2021-03-30
- Send values of `APP_ENV` and `APP_DEBUG` to the API.

## 1.0.0 - 2021-03-01

## 0.0.13 - 2021-02-03
 - 🤷‍♂️ Forgot why I parsed the phpinfo() instead of just calling phpversion().
 - Add GitHub issue template.
 - Add GitHub test workflow.
 - Add PHPUnit tests.
 - Support v2 of [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv).

## 0.0.12 - 2020-11-16
 - Support for PHP 8.
 - Change frequency of scheduled task to every 10 minutes.

## 0.0.11 - 2020-09-07

- Update package dependencies.
- Add `vursion:publish` command to make publishing the config file easier to remember.

## 0.0.10 - 2020-07-22

- Support v5 of [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv).
- Support v7 of [guzzle/guzzle](https://github.com/guzzle/guzzle).

## 0.0.9 - 2020-05-14

- Fix possible `NamespaceNotFoundException`.

## 0.0.8 - 2020-04-02

- Fix package dependencies.

## 0.0.7 - 2020-04-02

- Use a setting to enable/disable sending data to the API.

## 0.0.6 - 2020-03-31

- Use `URL::signedRoute()` on Laravel installations >= 5.6.12 instead of calculating the hash ourselves.

## 0.0.5 - 2020-03-31

- Only send data if API key is set.

## 0.0.4 - 2020-03-25

- Support both v3 and v4 of [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv).

## 0.0.3 - 2020-03-20

- Support both v3 and v4 of [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv).

## 0.0.2 - 2020-03-09

- Laravel installations >= 5.6.12 use a signed URL/route to protect the route that exposes the non PHP CLI version.

## 0.0.1 - 2020-03-03

- initial release.
