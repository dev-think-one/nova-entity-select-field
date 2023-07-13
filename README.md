# Laravel Nova Entity Select Field

![Packagist License](https://img.shields.io/packagist/l/think.studio/nova-entity-select-field?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/nova-entity-select-field)](https://packagist.org/packages/think.studio/nova-entity-select-field)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/nova-entity-select-field)](https://packagist.org/packages/think.studio/nova-entity-select-field)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/nova-entity-select-field/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-entity-select-field/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/nova-entity-select-field/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-entity-select-field/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/nova-entity-select-field/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-entity-select-field/?branch=main)

Package allow implement functionality similar to "BelongsTo" but for general fields purpose, not for relation one (for example flexible content).

![preview-form.png](docs%2Fassets%2Fpreview-form.png)
![preview-details.png](docs%2Fassets%2Fpreview-details.png)
![preview-index.png](docs%2Fassets%2Fpreview-index.png)

| Nova | Package |
|------|------|
| V4   | V1   |

## Installation

You can install the package via composer:

```bash
composer require think.studio/nova-entity-select-field
```

## Usage

```php
public function fields() {
    return [
        \NovaEntitySelectField\EntitySelect::make(Member::class),
        \NovaEntitySelectField\EntitySelect::make(Member::class, 'Main Member', 'other_main_member')
                ->nullable()
                ->limit(4),
    ];
}
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
