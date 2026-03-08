<?php

namespace Numverify\Exception;

/**
 * Thrown when the Numverify API returns an API response that is unexpected
 */
class NumverifyApiResponseException extends \RuntimeException
{
    /**
     * NumverifyApiResponseException constructor
     *
     * @phpstan-ignore property.onlyWritten
     */
    public function __construct(string $message, private readonly \stdClass $phoneNumberData)
    {
        parent::__construct($message);
    }
}
