<?php

namespace Numverify;

use Numverify\Country;
use Numverify\Exception\NumverifyApiFailureException;
use Numverify\PhoneNumber;
use Numverify\PhoneNumber\PhoneNumberInterface;

/**
 * Numverify API
 *  - validatePhoneNumber
 *  - getCountries
 */
class Api
{
    private const HTTP_URL  = 'http://apilayer.net/api';
    private const HTTPS_URL = 'https://apilayer.net/api';

    private string $accessKey;
    private \GuzzleHttp\ClientInterface $client;

    /**
     * Api constructor
     *
     * @param string                           $accessKey  API access key
     * @param bool                             $useHttps   Optional flag to determine if API calls should use http or https
     * @param \GuzzleHttp\ClientInterface|null $guzzle     Optional parameter to provide your own Guzzle client
     */
    public function __construct(string $accessKey, bool $useHttps = false, ?\GuzzleHttp\ClientInterface $guzzle = null)
    {
        $this->accessKey = $accessKey;
        $this->client    = $guzzle ?? new \GuzzleHttp\Client(['base_uri' => $this->getUrl($useHttps)]);
    }

    /**
     * Validate a phone number
     *
     * @return PhoneNumberInterface|PhoneNumber\ValidPhoneNumber|PhoneNumber\InvalidPhoneNumber
     *
     * @throws \RuntimeException
     */
    public function validatePhoneNumber(string $phoneNumber, string $countryCode = ''): PhoneNumberInterface
    {
        $query = [
            'access_key' => $this->accessKey,
            'number'     => $phoneNumber,
        ];
        if (strlen($countryCode) > 0) {
            $query['country_code'] = $countryCode;
        }

        $result = $this->client->request(
            'GET',
            '/validate',
            [
                'query' => $query
            ]
        );
        $this->validateResponse($result);

        /** @var \stdClass $body */
        $body = json_decode((string) $result->getBody());
        return PhoneNumber\Factory::create($body);
    }

    /**
     * Get list of countries
     *
     * @throws \RuntimeException
     */
    public function getCountries(): Country\Collection
    {
        $result = $this->client->request(
            'GET',
            '/countries',
            [
                'query' => [
                    'access_key' => $this->accessKey,
                ]
            ]
        );
        $this->validateResponse($result);

        /** @var array<string, array{country_name: string, dialling_code: string}> $body */
        $body = json_decode((string) $result->getBody(), true);

        $countries = array_map(
            function (array $country, string $countryCode): Country\Country {
                return new Country\Country($countryCode, $country['country_name'], $country['dialling_code']);
            },
            $body,
            array_keys($body)
        );
        return new Country\Collection(...$countries);
    }

    /**
     * Get the URL to use for API calls
     */
    private function getUrl(bool $useHttp): string
    {
        return match ($useHttp) {
            true  => self::HTTPS_URL,
            false => self::HTTP_URL,
        };
    }

    /**
     * Validate the response
     *
     * @throws NumverifyApiFailureException if the response is non 200 or success field is false
     */
    private function validateResponse(\Psr\Http\Message\ResponseInterface $response): void
    {
        if ($response->getStatusCode() !== 200) {
            throw new NumverifyApiFailureException($response);
        }

        /** @var \stdClass|null $body */
        $body = json_decode((string) $response->getBody());
        if (isset($body->success) && $body->success === false) {
            throw new NumverifyApiFailureException($response);
        }
    }
}
