<?php

namespace Numverify\Tests;

use Numverify;

class ApiPhoneNumberTest extends \PHPUnit\Framework\TestCase
{
    const ACCESS_KEY = 'SomeAccessKey';

    /* ********************** *
     * API SUCCESS TEST CASES
     * ********************** */

    /**
     * @testCase     validatePhoneNumber success - valid phone number
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testValidatePhoneNumberValidPhoneNumber(bool $useHttps)
    {
        // Given
        $response = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')
            ->willReturn('{
                "valid": true,
                "number": "14158586273",
                "local_format": "4158586273",
                "international_format": "+14158586273",
                "country_prefix": "+1",
                "country_code": "US",
                "country_name": "United States of America",
                "location": "Novato",
                "carrier": "AT&T Mobility LLC",
                "line_type": "mobile"
            }');
        $client = $this->createPartialMock(\GuzzleHttp\Client::class, ['request']);
        $client->method('request')->willReturn($response);

        // And
        $api = new Numverify\Api(self::ACCESS_KEY, $useHttps, $client);

        // When
        $phoneNumberToValidate = '14158586273';
        $phoneNumber = $api->validatePhoneNumber($phoneNumberToValidate);

        // Then
        $this->assertInstanceOf(Numverify\PhoneNumber\ValidPhoneNumber::class, $phoneNumber);
    }

    /**
     * @testCase     validatePhoneNumber success - invalid phone number
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testValidatePhoneNumberInvalidPhoneNumber(bool $useHttps)
    {
        // Given
        $response = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')
            ->willReturn('{
                "valid":false,
                "number":"183155511",
                "local_format":"",
                "international_format":"",
                "country_prefix":"",
                "country_code":"",
                "country_name":"",
                "location":"",
                "carrier":"",
                "line_type":null
            }');
        $client = $this->createPartialMock(\GuzzleHttp\Client::class, ['request']);
        $client->method('request')->willReturn($response);

        // And
        $api = new Numverify\Api(self::ACCESS_KEY, $useHttps, $client);

        // When
        $phoneNumberToValidate = '18314262511';
        $phoneNumber = $api->validatePhoneNumber($phoneNumberToValidate);

        // Then
        $this->assertInstanceOf(Numverify\PhoneNumber\InvalidPhoneNumber::class, $phoneNumber);
    }

    /* ******************** *
     * EXCEPTION TEST CASES
     * ******************** */

    /**
     * @testCase     validatePhoneNumber exception - invalid access key
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testValidatePhoneNumberInvalidAccessKey(bool $useHttps)
    {
        // Given
        $response = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')
            ->willReturn('{
                "success":false,
                "error":{
                    "code":101,
                    "type":"invalid_access_key",
                    "info":"You have not supplied a valid API Access Key. [Technical Support: support@apilayer.com]"
                }
            }');
        $client = $this->createPartialMock(\GuzzleHttp\Client::class, ['request']);
        $client->method('request')->willReturn($response);

        // And
        $invalidAccessKey = 'InvalidAccessKey';
        $api = new Numverify\Api($invalidAccessKey, $useHttps, $client);

        // Then
        $this->expectException(Numverify\Exception\NumverifyApiFailureException::class);
        $this->expectExceptionMessage('Type:invalid_access_key Code:101 Info:You have not supplied a valid API Access Key. [Technical Support: support@apilayer.com]');

        // When
        $phoneNumberToValidate = '18314262511';
        $phoneNumber = $api->validatePhoneNumber($phoneNumberToValidate);
    }

    /**
     * @testCase     validatePhoneNumber exception - API bad response
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testValidatePhoneNumberBadResponse(bool $useHttps)
    {
        // Given
        $response = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $response->method('getStatusCode')->willReturn(500);
        $response->method('getReasonPhrase')->willReturn('Internal Server Error');
        $response->method('getBody')->willReturn('server error');
        $client = $this->createPartialMock(\GuzzleHttp\Client::class, ['request']);
        $client->method('request')->willReturn($response);

        // And
        $api = new Numverify\Api(self::ACCESS_KEY, $useHttps, $client);

        // Then
        $this->expectException(Numverify\Exception\NumverifyApiFailureException::class);
        $this->expectExceptionMessage('Unknown error - 500 Internal Server Error');

        // When
        $phoneNumberToValidate = '18314262511';
        $phoneNumber = $api->validatePhoneNumber($phoneNumberToValidate);
    }

    /**
     * @testCase     validatePhoneNumber exception - API response missing expected field "carrier"
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testValidatePhoneNumberApiResponseMissingData(bool $useHttps)
    {
        // Given
        $response = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')
            ->willReturn('{
                "valid": true,
                "number": "14158586273",
                "local_format": "4158586273",
                "international_format": "+14158586273",
                "country_prefix": "+1",
                "country_code": "US",
                "country_name": "United States of America",
                "location": "Novato",
                "line_type": "mobile"
            }');
        $client = $this->createPartialMock(\GuzzleHttp\Client::class, ['request']);
        $client->method('request')->willReturn($response);

        // And
        $api = new Numverify\Api(self::ACCESS_KEY, $useHttps, $client);

        // Then
        $this->expectException(Numverify\Exception\NumverifyApiResponseException::class);
        $this->expectExceptionMessage('API response does not contain the expected field carrier');

        // When
        $phoneNumberToValidate = '14158586273';
        $phoneNumber = $api->validatePhoneNumber($phoneNumberToValidate);
    }

    /* ************* *
     * DATA PROVIDER
     * ************* */

    /**
     * @return array
     */
    public function dataProviderForHttp(): array
    {
        return [
            [true],
            [false],
        ];
    }
}
