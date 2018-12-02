<?php
namespace Numverify\PhoneNumber;

/**
 * PhoneNumber Factory
 * Role: Factory class to create the appropriate [p;u,pr[joc PhoneNumber object
 */
class Factory
{
    /**
     * Factory creation method
     *
     * @param \stdClass $validatedPhoneNumber
     *
     * @return PhoneNumberInterface
     */
    public static function create(\stdClass $validatedPhoneNumber): PhoneNumberInterface
    {
        if (boolval($validatedPhoneNumber->valid) === false) {
            return new InvalidPhoneNumber($validatedPhoneNumber);
        }

        return new ValidPhoneNumber($validatedPhoneNumber);
    }
}
