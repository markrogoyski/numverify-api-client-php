<?php

namespace Numverify\PhoneNumber;

/**
 * PhoneNumber Factory
 * Role: Factory class to create the appropriate PhoneNumber object
 */
class Factory
{
    /**
     * Factory creation method
     */
    public static function create(\stdClass $validatedPhoneNumber): PhoneNumberInterface
    {
        return match (\boolval($validatedPhoneNumber->valid)) {
            true  => new ValidPhoneNumber($validatedPhoneNumber),
            false => new InvalidPhoneNumber($validatedPhoneNumber),
        };
    }
}
