# Cookie Law PresstiFy Plugin

[![Latest Version](https://img.shields.io/badge/release-2.0.44-blue?style=for-the-badge)](https://svn.tigreblanc.fr/presstify-plugins/cookie-law/tags/2.0.44)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE.md)

**Cookie Law** allow manage the privacy policy of apps.

## Installation

```bash
composer require presstify-plugins/cookie-law
```

## Setup

### Declaration

```php
// config/app.php
return [
      //...
      'providers' => [
          //...
          \tiFy\Plugins\CookieLaw\CookieLawServiceProvider::class,
          //...
      ];
      // ...
];
```

### Configuration

```php
// config/theme-suite.php
// @see /vendor/presstify-plugins/cookie-law/config/cookie-law.php
return [
      //...

      // ...
];
```
