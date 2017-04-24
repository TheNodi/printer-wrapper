# PHP Printer Wrapper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thenodi/printer-wrapper.svg?style=flat-square)](https://packagist.org/packages/thenodi/printer-wrapper)
[![Build Status](https://img.shields.io/travis/TheNodi/printer-wrapper/master.svg?style=flat-square)](https://travis-ci.org/TheNodi/printer-wrapper)
[![Total Downloads](https://img.shields.io/packagist/dt/thenodi/printer-wrapper.svg?style=flat-square)](https://packagist.org/packages/thenodi/printer-wrapper)

Wrapper around unix `lp` commands used to send file to a printer, manage connected printers and see print queue.

## Installation

You can install the package via composer:

```bash
composer require thenodi/printer-wrapper
```

## Usage

### Basic Usage

Print a file using the default printer.
```php
<?php
(new \TheNodi\PrinterWrapper\PrinterManager())->printFile('/path/to/file.txt');
```

List all printers
```php
<?php
(new \TheNodi\PrinterWrapper\PrinterManager())->printers();
// => Printer[]
```

### Page Settings

Print a page in landscape mode.
```php
<?php
(new \TheNodi\PrinterWrapper\PrinterManager())
    ->landscape()
    ->printFile('/path/to/file.txt');
```

Print a letter.
```php
<?php
(new \TheNodi\PrinterWrapper\PrinterManager())
    ->media(\TheNodi\PrinterWrapper\Printer::MEDIA_LETTER)
    ->printFile('/path/to/file.txt');
```

Print a document two-sided.
```php
<?php
(new \TheNodi\PrinterWrapper\PrinterManager())
    ->twoSided()
    ->printFile('/path/to/file.txt');
```

If you need to pass a custom option to the `lp` command use the `setOption` method.
```php
<?php
(new \TheNodi\PrinterWrapper\PrinterManager())
    ->setOption('only-name')
    ->setOption('name', 'value')
    ->printFile('/path/to/file.txt');
// => lp -o only-name -o name=value ...
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Credits

- [Leonardo Nodari](https://github.com/TheNodi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
