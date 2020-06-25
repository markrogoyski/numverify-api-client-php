<?php

namespace Numverify\PhoneNumber;

use Numverify\Exception\NumverifyApiResponseException;

/**
 * InvalidPhoneNumber
 * Role: Value object to represent a phone number that the Numverify returned as invalid
 */
class InvalidPhoneNumber implements PhoneNumberInterface, \JsonSerializable
{
    /** @var bool */
    private $valid;

    /** @var string */
    private $number;

    const FIELDS = ['valid', 'number'];

    /**
     * InvalidPhoneNumber constructor
     *
     * @param \stdClass $validatedPhoneNumber
     */
    public function __construct(\stdClass $validatedPhoneNumber)
    {
        $this->verifyPhoneNumberData($validatedPhoneNumber);

        $this->valid  = boolval($validatedPhoneNumber->valid);
        $this->number = $validatedPhoneNumber->number;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
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
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'valid'  => $this->valid,
            'number' => $this->number,
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
