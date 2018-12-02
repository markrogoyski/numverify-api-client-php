<?php
namespace Numverify\Exception;

/**
 * Thrown when the Numverify API returns an API response that is unexpected
 */
class NumverifyApiResponseException extends \RuntimeException
{
    /** @var \stdClass */
    private $phoneNumberData;

    /**
     * NumverifyApiResponseException constructor
     *
     * @param string    $message
     * @param \stdClass $phoneNumberData
     */
    public function __construct(string $message, \stdClass $phoneNumberData)
    {
        $this->phoneNumberData = $phoneNumberData;

        parent::__construct($message);
    }
}
