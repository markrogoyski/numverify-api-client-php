<?php

namespace Numverify\PhoneNumber;

use Numverify\Exception\NumverifyApiResponseException;

/**
 * InvalidPhoneNumber
 * Role: Value object to represent a phone number that the Numverify returned as invalid
 */
readonly class InvalidPhoneNumber implements PhoneNumberInterface, \JsonSerializable
{
    private bool $valid;
    private string $number;

    private const FIELDS = ['valid', 'number'];

    /**
     * InvalidPhoneNumber constructor
     */
    public function __construct(\stdClass $validatedPhoneNumber)
    {
        $this->verifyPhoneNumberData($validatedPhoneNumber);

        $this->valid  = \boolval($validatedPhoneNumber->valid);
        $this->number = (string) $validatedPhoneNumber->number; // @phpstan-ignore cast.string
    }

    public function isValid(): false
    {
        return false;
    }

    public function getNumber(): string
    {
        return $this->number;
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
