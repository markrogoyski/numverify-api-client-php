<?php

namespace Numverify\Tests\PhoneNumber;

use Numverify\Exception\NumverifyApiFailureException;
use PHPUnit\Framework\MockObject\MockObject;

class NumverifyApiFailureExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase getStatusCode
     */
    public function testGetStatusCode()
    {
        // Given
        $e = new NumverifyApiFailureException($this->response);

        // When
        $statusCode = $e->getStatusCode();

        // Then
        $this->assertSame(self::STATUS_CODE, $statusCode);
    }

    /**
     * @testCase getReasonPhrase
     */
    public function testGetReasonPhrase()
    {
        // Given
        $e = new NumverifyApiFailureException($this->response);

        // When
        $reasonPhrase = $e->getReasonPhrase();

        // Then
        $this->assertSame(self::REASON_PHRASE, $reasonPhrase);
    }

    /**
     * @testCase getBody
     */
    public function testGetBody()
    {
        // Given
        $e = new NumverifyApiFailureException($this->response);

        // When
        $body = $e->getBody();

        // Then
        $this->assertSame(self::BODY, $body);
    }

    const STATUS_CODE   = 500;
    const REASON_PHRASE = 'Internal Server Error';
    const BODY          = 'server error';

    /** @var \Psr\Http\Message\ResponseInterface|MockObject */
    private $response;

    public function setUp()
    {
        $this->response = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $this->response->method('getStatusCode')->willReturn(self::STATUS_CODE);
        $this->response->method('getReasonPhrase')->willReturn(self::REASON_PHRASE);
        $this->response->method('getBody')->willReturn(self::BODY);
    }
}
