<?php

namespace Numverify\Exception;

/**
 * Thrown when the Numverify API returns a failure response
 */
class NumverifyApiFailureException extends \RuntimeException
{
    private int $statusCode;
    private string $reasonPhrase;
    private \Psr\Http\Message\StreamInterface $body;

    /**
     * NumverifyApiFailureException constructor
     */
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        $this->statusCode   = $response->getStatusCode();
        $this->reasonPhrase = $response->getReasonPhrase();
        $this->body         = $response->getBody();

        $message = $this->parseMessageFromBody((string) $this->body);

        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getBody(): string
    {
        return (string) $this->body;
    }

    /**
     * Parse JSON body error message
     *
     * Expecting a JSON body like:
     * {
     *     "success":false,
     *     "error":{
     *         "code":101,
     *         "type":"invalid_access_key",
     *         "info":"You have not supplied a valid API Access Key. [Technical Support: support@apilayer.com]"
     *     }
     * }
     */
    private function parseMessageFromBody(string $jsonBody): string
    {
        /** @var object{error?: object{type: string, code: int, info: string}}|null $body */
        $body = \json_decode($jsonBody);

        if (!isset($body->error)) {
            return 'Unknown error - ' . $this->statusCode . ' ' . $this->getReasonPhrase();
        }

        $error = $body->error;
        return \sprintf('Type:%s Code:%d Info:%s', $error->type, $error->code, $error->info);
    }
}
