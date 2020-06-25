Numverify API Client Library for PHP
====================================

Numverify phone number validation and country API client library for PHP.

[![Coverage Status](https://coveralls.io/repos/github/markrogoyski/numverify-api-client-php/badge.svg?branch=master)](https://coveralls.io/github/markrogoyski/numverify-api-client-php?branch=master)
[![Build Status](https://travis-ci.org/markrogoyski/numverify-api-client-php.svg?branch=master)](https://travis-ci.org/markrogoyski/numverify-api-client-php)
[![License](https://poser.pugx.org/markrogoyski/math-php/license)](https://packagist.org/packages/markrogoyski/numverify-api-client-php)

Features
--------
 * Phone number validation API
   * Validate phone numbers
   * Carrier information
   * Line type
   * Location info: country, local information
   * Phone number formats
 * Countries API
   * List of countries
   * Country names, country codes, dialing codes
   
Numverify API documentation: https://numverify.com/documentation

Setup
-----

 Add the library to your `composer.json` file in your project:

```javascript
{
  "require": {
      "markrogoyski/numverify-api-client-php": "2.*"
  }
}
```

Use [composer](http://getcomposer.org) to install the library:

```bash
$ php composer.phar install
```

Composer will install Numverify API Client Library for PHP inside your vendor folder. Then you can add the following to your
.php files to the use library with Autoloading.

```php
require_once(__DIR__ . '/vendor/autoload.php');
```

Alternatively, use composer on the command line to require and install Numverify API Client Library:

```
$ php composer.phar require markrogoyski/numverify-api-client-php:2.*
```

### Minimum Requirements
 * PHP 7.2
 
 Note: For PHP 7.0 and 7.1, use v1.0 (require markrogoyski/numverify-api-client-php":1.*)

Usage
-----

### Create New API
```php
$accessKey = 'AccountAccessKeyGoesHere';
$api       = new \Numverify\Api($accessKey);
```
 
### Phone Number Validation API
```php
$phoneNumber          = '14158586273';
$validatedPhoneNumber = $api->validatePhoneNumber($phoneNumber);
 
// Phone number information
if ($validatedPhoneNumber->isValid()) {
    $number              = $validatedPhoneNumber->getNumber();               // 14158586273
    $localFormat         = $validatedPhoneNumber->getLocalFormat();          // 4158586273
    $internationalPrefix = $validatedPhoneNumber->getInternationalFormat();  // +14158586273
    $countryPrefix       = $validatedPhoneNumber->getCountryPrefix();        // +1
    $countryCode         = $validatedPhoneNumber->getCountryCode();          // US
    $countryName         = $validatedPhoneNumber->getCountryName();          // United States of America
    $location            = $validatedPhoneNumber->getLocation();             // Novato
    $carrier             = $validatedPhoneNumber->getCarrier();              // AT&T Mobility LLC
    $lineType            = $validatedPhoneNumber->getLineType();             // mobile
}
 
// PHP Interfaces
$stringRepresentation = (string) $validatedPhoneNumber;
$jsonRepresentation   = json_encode($validatedPhoneNumber);
``` 
 
### Countries API
```php
$countries = $api->getCountries();
 
// Find countries (by country code or by name)
$unitedStates = $countries->findByCountryCode('US');
$japan        = $countries->findByCountryName('Japan');
 
// Country information
$usCountryCode = $unitedStates->getCountryCode();  // US
$usCountryName = $unitedStates->getCountryName();  // United States
$usDialingCode = $unitedStates->getDialingCode();  // +1
 
$japanCountryCode = $japan->getCountryCode();       // JP
$japanCountryName = $japan->getCountryName();       // Japan
$japanDialingCode = $japan->getDialingCode();       // +81
 
// Country collection is iterable
foreach ($countries as $country) {
    $countryCode = $country->getCountryCode();
    $countryName = $country->getCountryName();
    $dialingCode = $country->getDialingCode();
}
 
// Country collection PHP interfaces
$numberOfCountries  = count($countries);
$jsonRepresentation = json_encode($numberOfCountries);
 
// Country PHP interfaces
$stringRepresentation = (string) $unitedStates;      // US: United States (+1)
$jsonRepresentation   = json_encode($unitedStates);
```

### Options
```php
// Construct API to use HTTPS for API calls
$useHttps = true;
$api      = new \Numverify\Api($accessKey, $useHttps);  // Optional second parameter
```

### Exceptions
API failures throw a ```NumverifyApiFailureException```
```php
// Numverify API server error
try {
    $validatedPhoneNumber = $api->validatePhoneNumber($phoneNumber);
} catch (\Numverify\Exception\NumverifyApiFailureException $e) {
    $statusCode = $e->getStatusCode();  // 500
    $message    = $e->getMessage();     // Unknown error - 500 Internal Server Error
}

// Numverify API failure response
try {
    $validatedPhoneNumber = $api->validatePhoneNumber($phoneNumber);
} catch (\Numverify\Exception\NumverifyApiFailureException $e) {
    $statusCode = $e->getStatusCode();  // 200
    $message    = $e->getMessage();     // Type:invalid_access_key Code:101 Info:You have not supplied a valid API Access Key.
}
```

Unit Tests
----------

```bash
$ cd tests
$ phpunit
```

[![Coverage Status](https://coveralls.io/repos/github/markrogoyski/numverify-api-client-php/badge.svg?branch=master)](https://coveralls.io/github/markrogoyski/numverify-api-client-php?branch=master)

Standards
---------

Numverify API Client Library for PHP conforms to the following standards:

 * PSR-1 - Basic coding standard (http://www.php-fig.org/psr/psr-1/)
 * PSR-2 - Coding style guide (http://www.php-fig.org/psr/psr-2/)
 * PSR-4 - Autoloader (http://www.php-fig.org/psr/psr-4/)

License
-------

Numverify API Client Library for PHP is licensed under the MIT License. 
