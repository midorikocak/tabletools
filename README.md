# Table Tools

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

In an app you have to deal with tables. This library gives you some handy tools to deal with them.

## Install

Via Composer

``` bash
$ composer require midorikocak/tabletools
```

## Usage

You can access and operate 2 dimensional associative data using 3 kind of tables. `DatabaseTable`, 
`ArrayTable`,`CsvTable`.

Most common operations with tables are defined in `TableInterface`.

```php
<?php

declare(strict_types=1);

namespace midorikocak\tabletools;

interface TableInterface
{
    public function sort(string $key, $order = 'ASC'): self;

    public function columns($keys): self;

    public function filter(string $key, $value): self;

    public function search(string $key, $value): self;

    public function range(int $offset, ?int $limit = null): self;

    public function paginate(int $page = 0, int $pageSize = 10): self;

    public function run(): array;
}
```


### DatabaseTable

To use the `DatabaseTable` class, inject it with an object that implements `midorikocak/nanodb/tabletoolsbaseInterface`. 
Or a simple PDO wrapper with same methods)

```php
$pdo = new PDO();
$db = new \midorikocak\nanodb\Database($pdo);
$databaseTable = new \midorikocak\tabletools\DatabaseTable($db);
```

### ArrayTable

If your data is already in memory, you can use `ArrayTable`. 

```php
$data = getArrayFromCsv('tests/small-name.csv');
$arrayTable = new ArrayTable($data);
```

### CsvTable

If you deal with CSV files, you can import the data in a `CsvTable`.

```php
$csvTable = new CsvTable('tests/small-name.csv');
```


### Columns

Get only specified columns.

```php
$columns = $this->arrayTable->columns(['first_name', 'last_name'])->run();
```

### Filter

Filters rows other than with specified value.

```php
$filtered = $this->arrayTable->filter('username', 'midorikocak')->run();
```

### Sort

Sort table by specified column.

```php
$sorted = $this->arrayTable->sort('username', 'DESC')->run();
```

### Search

Search table by value.

```php
$found = $this->arrayTable->search('username', 'kocak')->run();
```

### Range

Retrieve a range of items. 

```php
// Retrieves 10 items after 30th
$range = $this->arrayTable->range(30, 10)->run();
```

### Paginate

Retrieve a page of items. 

```php
// Retrieves 50 more items after first 50 item. 
$page = $this->arrayTable->paginate(2, 50)->run();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mtkocak@gmail.com instead of using the issue tracker.

## Credits

- [Midori Kocak][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/midorikocak/tabletools.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/midorikocak/tabletools/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/midorikocak/tabletools.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/midorikocak/tabletools.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/midorikocak/tabletools.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/midorikocak/tabletools
[link-travis]: https://travis-ci.org/midorikocak/tabletools
[link-scrutinizer]: https://scrutinizer-ci.com/g/midorikocak/tabletools/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/midorikocak/tabletools
[link-downloads]: https://packagist.org/packages/midorikocak/tabletools
[link-author]: https://github.com/midorikocak
[link-contributors]: ../../contributors
