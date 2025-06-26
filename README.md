[![GitHub Release](https://img.shields.io/github/v/release/lufiipe/insee-sierene)](https://github.com/lufiipe/insee-sierene/releases)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/lufiipe/insee-sierene/php_run_tests.yml)](https://github.com/lufiipe/insee-sierene/actions)
[![Static Badge](https://img.shields.io/badge/PHPStan-level_9-brightgreen)](https://phpstan.org/)
[![GitHub License](https://img.shields.io/github/license/lufiipe/insee-sierene?color=yellow)](LICENSE)
[![Static Badge](https://img.shields.io/badge/insee_Sirene-v3.11-blue)](https://api.insee.fr/catalogue/site/themes/wso2/subthemes/insee/pages/item-info.jag?name=Sirene&version=V3.11&provider=insee)

# INSEE Sirene client for PHP

The INSEE Sirene client package is a PHP library that provides a simple and easy-to-use interface for interacting with the INSEE API. It allows you to retrieve legal data, such as company information.

With this package, you can:

- :white_check_mark: Advanced search
- :white_check_mark: Facets
- :white_check_mark: Iterates over the items in the collection
- :white_check_mark: API Rate Limiting
- :white_check_mark: Event listener

## Install

```
composer require lufiipe/insee-sierene
```

## Usage

```php
require_once "vendor/autoload.php";
use LuFiipe\InseeSierene\Exception\SireneException;
use LuFiipe\InseeSierene\Parameters\SearchParameters;
use LuFiipe\InseeSierene\Sirene;

$sirene = new Sirene('YOUR-API-KEY');

// Get legal entity details by SIREN number
$sirene->siren('120027016')->getBody();

// Get establishment details by SIRET Number
$sirene->siret('12002701600563')->getBody();

// Searches for legal entities whose name currently contains or previously contained the term "INSEE"
$parameters = (new SearchParameters)
    ->setQuery('periode(denominationUniteLegale:INSEE)');
$collection = $sirene->searchLegalUnits($parameters);
$collection->each(function (array $legalUnit) {
    var_dump($legalUnit);
});

// Retrieves establishments containing the name "WWF"
$parameters = (new SearchParameters)
    ->setQuery('denominationUniteLegale:"WWF"');
$collection = $sirene->searchEstablishments($parameters);
$collection->each(function (array $establishment) {
    var_dump($establishment);
});

// INSEE Sirene Service Status
try {
    $res = $sirene->informations();
} catch (SireneException $e) {
    // ../..
}
```

## Documentation

You can find detailed instructions on how to use this package at [the dedicated documentation site](https://lufiipe.github.io/insee-sirene-docs/).


## Tests

Copy the file `phpunit.xml.dist` to `phpunit.xml` and update the value of the `INSEE_API_KEY` variable with your api key.

Then, run:

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.