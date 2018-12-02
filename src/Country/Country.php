<?php
namespace Numverify\Country;

/**
 * Country
 * Role: Value object that represents a callable country
 */
class Country implements \JsonSerializable
{
    /** @var string */
    private $countryCode;

    /** @var string */
    private $countryName;

    /** @var string */
    private $dialingCode;

    /**
     * Country constructor
     *
     * @param string $countryCode
     * @param string $countryName
     * @param string $dialingCode
     */
    public function __construct(string $countryCode, string $countryName, string $dialingCode)
    {
        $this->countryCode = $countryCode;
        $this->countryName = $countryName;
        $this->dialingCode = $dialingCode;
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
     * Get dialing code
     *
     * @return string
     */
    public function getDialingCode(): string
    {
        return $this->dialingCode;
    }

    /**
     * String representation
     * CountryCode: CountryName (DialingCode)
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s: %s (%s)', $this->countryCode, $this->countryName, $this->dialingCode);
    }

    /**
     * JsonSerializable interface
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'countryCode'  => $this->countryCode,
            'countryName'  => $this->countryName,
            'diallingCode' => $this->dialingCode,
        ];
    }
}
