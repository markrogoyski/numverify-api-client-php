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
    const HTTP_URL  = 'http://apilayer.net/api';
    const HTTPS_URL = 'http://apilayer.net/api';

    /** @var string API access key */
    private $accessKey;

    /** @var \GuzzleHttp\ClientInterface */
    private $client;

    /**
     * Api constructor
     *
     * @param string                           $accessKey  API access key
     * @param bool                             $useHttps   Optional flag to determine if API calls should use http or https
     * @param \GuzzleHttp\ClientInterface|null $guzzle     Optional parameter to provide your own Guzzle client
     */
    public function __construct(string $accessKey, bool $useHttps = false, \GuzzleHttp\ClientInterface $guzzle = null)
    {
        $this->accessKey = $accessKey;
        $this->client    = $guzzle ?? new \GuzzleHttp\Client(['base_uri' => $this->getUrl($useHttps)]);
    }

    /**
     * Validate a phone number
     *
     * @param string $phoneNumber
     *
     * @return PhoneNumberInterface|PhoneNumber\ValidPhoneNumber|PhoneNumber\InvalidPhoneNumber
     *
     * @throws \RuntimeException
     */
    public function validatePhoneNumber(string $phoneNumber): PhoneNumberInterface
    {
        $result = $this->client->request(
            'GET',
            '/validate',
            [
                'query' => [
                    'access_key' => $this->accessKey,
                    'number'     => $phoneNumber,
                ]
            ]
        );
        $this->validateResponse($result);

        $body = json_decode($result->getBody());
        return PhoneNumber\Factory::create($body);
    }

    /**
     * Get list of countries
     *
     * @return Country\Collection
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

        $body = json_decode($result->getBody(), true);

        $countries = array_map(
            function (array $country, string $countryCode) {
                return new Country\Country($countryCode, $country['country_name'], $country['dialling_code']);
            },
            $body,
            array_keys($body)
        );
        return new Country\Collection(...$countries);
    }

    /**
     * Get the URL to use for API calls
     *
     * @param bool $useHttp
     *
     * @return string
     */
    private function getUrl(bool $useHttp): string
    {
        return $useHttp
            ? self::HTTPS_URL
            : self::HTTP_URL;
    }

    /**
     * Validate the response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @throws NumverifyApiFailureException if the response is non 200 or success field is false
     */
    private function validateResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        if ($response->getStatusCode() !== 200) {
            throw new NumverifyApiFailureException($response);
        }

        $body = json_decode($response->getBody());
        if (isset($body->success) && $body->success == false) {
            throw new NumverifyApiFailureException($response);
        }
    }
}
