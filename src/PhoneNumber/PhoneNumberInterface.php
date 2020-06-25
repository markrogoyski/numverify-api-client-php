<?php

namespace Numverify\PhoneNumber;

/**
 * Interface for all phone numbers returned from the Numverify validate API
 */
interface PhoneNumberInterface
{
    public function isValid(): bool;

    public function getNumber(): string;
}
