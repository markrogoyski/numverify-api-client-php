<?php

namespace Numverify\Tests\PhoneNumber;

use Numverify\Exception\NumverifyApiResponseException;
use Numverify\PhoneNumber\ValidPhoneNumber;

class ValidPhoneNumberTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase isValid
     */
    public function testIsValid()
    {
        // Given
        $phoneNumber = new ValidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $isValid = $phoneNumber->isValid();

        // Then
        $this->assertTrue($isValid);
    }

    /**
     * @testCase getters
     */
    public function testGetters()
    {
        // Given
        $phoneNumber = new ValidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $number              = $phoneNumber->getNumber();
        $localFormat         = $phoneNumber->getLocalFormat();
        $internationalFormat = $phoneNumber->getInternationalFormat();
        $countryPrefix       = $phoneNumber->getCountryPrefix();
        $countryCode         = $phoneNumber->getCountryCode();
        $countryName         = $phoneNumber->getCountryName();
        $location            = $phoneNumber->getLocation();
        $carrier             = $phoneNumber->getCarrier();
        $lineType            = $phoneNumber->getLineType();

        // Then
        $this->assertSame(self::NUMBER, $number);
        $this->assertSame(self::LOCAL_FORMAT, $localFormat);
        $this->assertSame(self::INTERNATIONAL_FORMAT, $internationalFormat);
        $this->assertSame(self::COUNTRY_PREFIX, $countryPrefix);
        $this->assertSame(self::COUNTRY_CODE, $countryCode);
        $this->assertSame(self::COUNTRY_NAME, $countryName);
        $this->assertSame(self::LOCATION, $location);
        $this->assertSame(self::CARRIER, $carrier);
        $this->assertSame(self::LINE_TYPE, $lineType);
    }

    /**
     * @testCase String representation
     */
    public function testToString()
    {
        // Given
        $phoneNumber = new ValidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $stringRepresentation = (string) $phoneNumber;

        // Then
        $this->assertSame(self::NUMBER, $stringRepresentation);
    }

    /**
     * @testCase JsonSerializable interface
     */
    public function testJsonSerialize()
    {
        // Given
        $phoneNumber = new ValidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $json = json_encode($phoneNumber);

        // Then
        $object = json_decode($json);
        $this->assertSame(self::VALID, $object->valid);
        $this->assertSame(self::NUMBER, $object->number);
        $this->assertSame(self::LOCAL_FORMAT, $object->localFormat);
        $this->assertSame(self::INTERNATIONAL_FORMAT, $object->internationalFormat);
        $this->assertSame(self::COUNTRY_PREFIX, $object->countryPrefix);
        $this->assertSame(self::COUNTRY_CODE, $object->countryCode);
        $this->assertSame(self::COUNTRY_NAME, $object->countryName);
        $this->assertSame(self::LOCATION, $object->location);
        $this->assertSame(self::CARRIER, $object->carrier);
        $this->assertSame(self::LINE_TYPE, $object->lineType);
    }

    /**
     * @testCase Debug info
     */
    public function testDebugInfo()
    {
        // Given
        $phoneNumber = new ValidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $debugInfo = print_r($phoneNumber, true);

        // Then
        $this->assertStringContainsString('valid', $debugInfo);
        $this->assertStringContainsString('number', $debugInfo);
        $this->assertStringContainsString('localFormat', $debugInfo);
        $this->assertStringContainsString('internationalFormat', $debugInfo);
        $this->assertStringContainsString('countryPrefix', $debugInfo);
        $this->assertStringContainsString('countryCode', $debugInfo);
        $this->assertStringContainsString('countryName', $debugInfo);
        $this->assertStringContainsString('location', $debugInfo);
        $this->assertStringContainsString('carrier', $debugInfo);
        $this->assertStringContainsString('lineType', $debugInfo);
    }

    /**
     * @testCase     Missing constructor data exception
     * @dataProvider dataProviderForFields
     * @param        string $missingField
     */
    public function testPhoneNumberDataValidation(string $missingField)
    {
        // Given
        unset($this->validatedPhoneNumberData->$missingField);

        // Then
        $this->expectException(NumverifyApiResponseException::class);

        // When
        $phoneNumber = new ValidPhoneNumber($this->validatedPhoneNumberData);
    }

    /**
     * @return array
     */
    public function dataProviderForFields(): array
    {
        return [
            ['valid'],
            ['number'],
            ['local_format'],
            ['international_format'],
            ['country_prefix'],
            ['country_code'],
            ['country_name'],
            ['location'],
            ['carrier'],
            ['line_type'],
        ];
    }

    private const VALID                = true;
    private const NUMBER               = '14158586273';
    private const LOCAL_FORMAT         = '4158586273';
    private const INTERNATIONAL_FORMAT = '+14158586273';
    private const COUNTRY_PREFIX       = '+1';
    private const COUNTRY_CODE         = 'US';
    private const COUNTRY_NAME         = 'United States of America';
    private const LOCATION             = 'Novato';
    private const CARRIER              = 'AT&T Mobility LLC';
    private const LINE_TYPE            = 'mobile';

    /** @var object */
    private $validatedPhoneNumberData;

    public function setUp(): void
    {
        $this->validatedPhoneNumberData = (object) [
            'valid'                => self::VALID,
            'number'               => self::NUMBER,
            'local_format'         => self::LOCAL_FORMAT,
            'international_format' => self::INTERNATIONAL_FORMAT,
            'country_prefix'       => self::COUNTRY_PREFIX,
            'country_code'         => self::COUNTRY_CODE,
            'country_name'         => self::COUNTRY_NAME,
            'location'             => self::LOCATION,
            'carrier'              => self::CARRIER,
            'line_type'            => self::LINE_TYPE,
        ];
    }
}
