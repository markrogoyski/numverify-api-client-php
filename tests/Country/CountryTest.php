<?php

namespace Numverify\Tests;

use Numverify\Country\Country;

class CountryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase getters
     */
    public function testGetters()
    {
        // Given
        $country = new Country(self::COUNTRY_CODE, self::COUNTRY_NAME, self::DIALLING_CODE);

        // When
        $countryCode  = $country->getCountryCode();
        $countryName  = $country->getCountryName();
        $diallingCode = $country->getDialingCode();

        // Then
        $this->assertSame(self::COUNTRY_CODE, $countryCode);
        $this->assertSame(self::COUNTRY_NAME, $countryName);
        $this->assertSame(self::DIALLING_CODE, $diallingCode);
    }

    /**
     * @testCase String representation
     */
    public function testStringRepresentation()
    {
        // Given
        $country = new Country(self::COUNTRY_CODE, self::COUNTRY_NAME, self::DIALLING_CODE);

        // When
        $stringRepresentation = (string)$country;

        // Then
        $this->assertSame('US: United States (+1)', $stringRepresentation);
    }

    /**
     * @testCase JsonSerialize interface
     */
    public function testJsonSerializeInterface()
    {
        // Given
        $country = new Country(self::COUNTRY_CODE, self::COUNTRY_NAME, self::DIALLING_CODE);

        // When
        $json = json_encode($country);

        // Then
        $object = json_decode($json);
        $this->assertSame(self::COUNTRY_CODE, $object->countryCode);
        $this->assertSame(self::COUNTRY_NAME, $object->countryName);
        $this->assertSame(self::DIALLING_CODE, $object->diallingCode);
    }

    /* ********* *
     * TEST DATA
     * ********* */

    private const COUNTRY_CODE  = 'US';
    private const COUNTRY_NAME  = 'United States';
    private const DIALLING_CODE = '+1';
}
