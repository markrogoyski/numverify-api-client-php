<?php

namespace Numverify\Tests;

use Numverify;
use Numverify\Country;
use PHPUnit\Framework\MockObject\MockObject;

class ApiCountryTest extends \PHPUnit\Framework\TestCase
{
    private const ACCESS_KEY = 'SomeAccessKey';

    /* ********************** *
     * API SUCCESS TEST CASES
     * ********************** */

    /**
     * @testCase     getCountries success
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testCountriesApiReturnsNumberOfCountries(bool $useHttps)
    {
        // Given
        $client = $this->aClient();
        $api    = new Numverify\Api(self::ACCESS_KEY, $useHttps, $client);

        // When
        $countryCollection = $api->getCountries();

        // Then
        $this->assertCount(3, $countryCollection);
    }

    /**
     * @testCase     getCountries success
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testCountriesReturnsCollectionOfCountries(bool $useHttps)
    {
        // Given
        $client = $this->aClient();
        $api    = new Numverify\Api(self::ACCESS_KEY, $useHttps, $client);

        // When
        $countryCollection = $api->getCountries();

        // Then
        $this->assertInstanceOf(Country\Collection::class, $countryCollection);
        foreach ($countryCollection as $countryCode => $country) {
            $this->assertInstanceOf(Country\Country::class, $country);
        }
    }

    /**
     * @testCase     getCountries success
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testCountriesReturnsExpectedCountries(bool $useHttps)
    {
        // Given
        $client            = $this->aClient();
        $api               = new Numverify\Api(self::ACCESS_KEY, $useHttps, $client);
        $expectedCountries = ['JP' => false, 'GB' => false, 'US' => false];

        // When
        $countryCollection = $api->getCountries();

        // Then
        foreach ($countryCollection as $countryCode => $country) {
            $expectedCountries[$countryCode] = true;
        }
        foreach ($expectedCountries as $countryCode => $seen) {
            $this->assertTrue($seen);
        }
    }

    /* ******************** *
     * EXCEPTION TEST CASES
     * ******************** */

    /**
     * @testCase     getCountries exception - invalid access key
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testCountriesInvalidAccessKey(bool $useHttps)
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
        $countryCollection = $api->getCountries();
    }

    /**
     * @testCase     getCountries exception - API bad response
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
        $phoneNumber = $api->getCountries();
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

    /* ***** *
     * GIVEN
     * ***** */

    /**
     * Given a client
     *
     * @return \Psr\Http\Message\ResponseInterface|MockObject
     */
    private function aClient()
    {
        $response = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')
            ->willReturn('{
                "JP":{"country_name":"Japan","dialling_code":"+81"},
                "GB":{"country_name":"United Kingdom","dialling_code":"+44"},
                "US":{"country_name":"United States","dialling_code":"+1"}
            }');
        $client = $this->createPartialMock(\GuzzleHttp\Client::class, ['request']);
        $client->method('request')->willReturn($response);

        return $client;
    }
}
