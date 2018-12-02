<?php
namespace Numverify\PhoneNumber;

use Numverify\Exception\NumverifyApiResponseException;

/**
 * ValidPhoneNumber
 * Role: Value object to represent a phone number that the Numverify returned as valid
 */
class ValidPhoneNumber implements PhoneNumberInterface, \JsonSerializable
{
    /** @var bool */
    private $valid;

    /** @var string */
    private $number;

    /** @var string */
    private $localFormat;

    /** @var string */
    private $internationalFormat;

    /** @var string */
    private $countryPrefix;

    /** @var string */
    private $countryCode;

    /** @var string */
    private $countryName;

    /** @var string */
    private $location;

    /** @var string */
    private $carrier;

    /** @var string */
    private $lineType;

    const FIELDS = [
        'valid', 'number', 'local_format', 'international_format', 'country_prefix', 'country_code', 'country_name', 'location', 'carrier', 'line_type',
    ];

    /**
     * ValidPhoneNumber constructor
     *
     * @param \stdClass $validatedPhoneNumberData
     */
    public function __construct(\stdClass $validatedPhoneNumberData)
    {
        $this->verifyPhoneNumberData($validatedPhoneNumberData);

        $this->valid               = boolval($validatedPhoneNumberData->valid);
        $this->number              = $validatedPhoneNumberData->number;
        $this->localFormat         = $validatedPhoneNumberData->local_format;
        $this->internationalFormat = $validatedPhoneNumberData->international_format;
        $this->countryPrefix       = $validatedPhoneNumberData->country_prefix;
        $this->countryCode         = $validatedPhoneNumberData->country_code;
        $this->countryName         = $validatedPhoneNumberData->country_name;
        $this->location            = $validatedPhoneNumberData->location;
        $this->carrier             = $validatedPhoneNumberData->carrier;
        $this->lineType            = $validatedPhoneNumberData->line_type;
    }

    /**
     * Is the phone number valid?
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }

    /**
     * Get phone number
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Get local format
     *
     * @return string
     */
    public function getLocalFormat(): string
    {
        return $this->localFormat;
    }

    /**
     * Get international format
     *
     * @return string
     */
    public function getInternationalFormat(): string
    {
        return $this->internationalFormat;
    }

    /**
     * Get country prefix
     *
     * @return string
     */
    public function getCountryPrefix(): string
    {
        return $this->countryPrefix;
    }

    /**
     * Get country code
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Get country name
     *
     * @return string
     */
    public function getCountryName(): string
    {
        return $this->countryName;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Get carrier
     *
     * @return string
     */
    public function getCarrier(): string
    {
        return $this->carrier;
    }

    /**
     * Get line type
     *
     * @return string
     */
    public function getLineType(): string
    {
        return $this->lineType;
    }

    /**
     * String representation
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->number;
    }

    /**
     * JsonSerialize interface
     *
     * @return array
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
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Verify the phone number data contains the expected fields
     *
     * @param \stdClass $phoneNumberData
     *
     * @throws NumverifyApiResponseException
     */
    private function verifyPhoneNumberData(\stdClass $phoneNumberData)
    {
        foreach (self::FIELDS as $field) {
            if (!isset($phoneNumberData->$field)) {
                throw new NumverifyApiResponseException("API response does not contain the expected field $field", $phoneNumberData);
            }
        }
    }
}
