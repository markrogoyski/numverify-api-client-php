<?php

namespace Numverify\Tests\PhoneNumber;

use Numverify\Exception\NumverifyApiResponseException;

class NumverifyApiResponseExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase getMessage
     */
    public function testGetMessage()
    {
        // Given
        $expectedMessage = 'Test message';
        $phoneNumberData = new \stdClass();
        $e               = new NumverifyApiResponseException($expectedMessage, $phoneNumberData);

        // When
        $message = $e->getMessage();

        // Then
        $this->assertSame($expectedMessage, $message);
    }
}
