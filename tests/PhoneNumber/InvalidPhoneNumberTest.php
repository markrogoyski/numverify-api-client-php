<?php

namespace Numverify\Tests\PhoneNumber;

use Numverify\Exception\NumverifyApiResponseException;
use Numverify\PhoneNumber\InvalidPhoneNumber;

class InvalidPhoneNumberTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase isValid
     */
    public function testIsValid()
    {
        // Given
        $phoneNumber = new InvalidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $isValid = $phoneNumber->isValid();

        // Then
        $this->assertFalse($isValid);
    }

    /**
     * @testCase getters
     */
    public function testGetters()
    {
        // Given
        $phoneNumber = new InvalidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $number = $phoneNumber->getNumber();

        // Then
        $this->assertSame(self::NUMBER, $number);
    }

    /**
     * @testCase String representation
     */
    public function testToString()
    {
        // Given
        $phoneNumber = new InvalidPhoneNumber($this->validatedPhoneNumberData);

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
        $phoneNumber = new InvalidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $json = json_encode($phoneNumber);

        // Then
        $object = json_decode($json);
        $this->assertSame(self::VALID, $object->valid);
        $this->assertSame(self::NUMBER, $object->number);
    }

    /**
     * @testCase Debug info
     */
    public function testDebugInfo()
    {
        // Given
        $phoneNumber = new InvalidPhoneNumber($this->validatedPhoneNumberData);

        // When
        $debugInfo = print_r($phoneNumber, true);

        // Then
        $this->assertContains('valid', $debugInfo);
        $this->assertContains('number', $debugInfo);
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
        $phoneNumber = new InvalidPhoneNumber($this->validatedPhoneNumberData);
    }

    /**
     * @return array
     */
    public function dataProviderForFields(): array
    {
        return [
            ['valid'],
            ['number'],
        ];
    }

    private const VALID  = false;
    private const NUMBER = '14158586273';

    /** @var object */
    private $validatedPhoneNumberData;

    public function setUp()
    {
        $this->validatedPhoneNumberData = (object) [
            'valid'                => self::VALID,
            'number'               => self::NUMBER,
        ];
    }
}
