<?php

namespace Numverify\PhoneNumber;

use Numverify\Exception\NumverifyApiResponseException;

/**
 * ValidPhoneNumber
 * Role: Value object to represent a phone number that the Numverify returned as valid
 */
readonly class ValidPhoneNumber implements PhoneNumberInterface, \JsonSerializable
{
    private bool $valid;
    private string $number;
    private string $localFormat;
    private string $internationalFormat;
    private string $countryPrefix;
    private string $countryCode;
    private string $countryName;
    private string $location;
    private string $carrier;
    private string $lineType;

    private const FIELDS = [
        'valid', 'number', 'local_format', 'international_format', 'country_prefix', 'country_code', 'country_name', 'location', 'carrier', 'line_type',
    ];

    /**
     * ValidPhoneNumber constructor
     */
    public function __construct(\stdClass $validatedPhoneNumberData)
    {
        $this->verifyPhoneNumberData($validatedPhoneNumberData);

        $this->valid               = boolval($validatedPhoneNumberData->valid);
        $this->number              = (string) $validatedPhoneNumberData->number;               // @phpstan-ignore cast.string
        $this->localFormat         = (string) $validatedPhoneNumberData->local_format;         // @phpstan-ignore cast.string
        $this->internationalFormat = (string) $validatedPhoneNumberData->international_format; // @phpstan-ignore cast.string
        $this->countryPrefix       = (string) $validatedPhoneNumberData->country_prefix;       // @phpstan-ignore cast.string
        $this->countryCode         = (string) $validatedPhoneNumberData->country_code;         // @phpstan-ignore cast.string
        $this->countryName         = (string) $validatedPhoneNumberData->country_name;         // @phpstan-ignore cast.string
        $this->location            = (string) $validatedPhoneNumberData->location;             // @phpstan-ignore cast.string
        $this->carrier             = (string) $validatedPhoneNumberData->carrier;              // @phpstan-ignore cast.string
        $this->lineType            = (string) $validatedPhoneNumberData->line_type;            // @phpstan-ignore cast.string
    }

    /**
     * Is the phone number valid?
     */
    public function isValid(): true
    {
        return true;
    }

    /**
     * Get phone number
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Get local format
     */
    public function getLocalFormat(): string
    {
        return $this->localFormat;
    }

    /**
     * Get international format
     */
    public function getInternationalFormat(): string
    {
        return $this->internationalFormat;
    }

    /**
     * Get country prefix
     */
    public function getCountryPrefix(): string
    {
        return $this->countryPrefix;
    }

    /**
     * Get country code
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Get country name
     */
    public function getCountryName(): string
    {
        return $this->countryName;
    }

    /**
     * Get location
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Get carrier
     */
    public function getCarrier(): string
    {
        return $this->carrier;
    }

    /**
     * Get line type
     */
    public function getLineType(): string
    {
        return $this->lineType;
    }

    /**
     * String representation
     */
    public function __toString(): string
    {
        return $this->number;
    }

    /**
     * JsonSerialize interface
     *
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'valid'               => $this->valid,
            'number'              => $this->number,
            'localFormat'         => $this->localFormat,
            'internationalFormat' => $this->internationalFormat,
            'countryPrefix'       => $this->countryPrefix,
            'countryCode'         => $this->countryCode,
            'countryName'         => $this->countryName,
            'location'            => $this->location,
            'carrier'             => $this->carrier,
            'lineType'            => $this->lineType,
        ];
    }

    /**
     * Debug info
     *
     * @return mixed[]
     */
    public function __debugInfo(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Verify the phone number data contains the expected fields
     *
     * @throws NumverifyApiResponseException
     */
    private function verifyPhoneNumberData(\stdClass $phoneNumberData): void
    {
        foreach (self::FIELDS as $field) {
            if (!isset($phoneNumberData->$field)) {
                throw new NumverifyApiResponseException("API response does not contain the expected field $field", $phoneNumberData);
            }
        }
    }
}
