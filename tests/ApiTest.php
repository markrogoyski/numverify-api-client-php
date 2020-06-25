<?php

namespace Numverify\Tests;

use Numverify;

class ApiTest extends \PHPUnit\Framework\TestCase
{
    const ACCESS_KEY = 'SomeAccessKey';

    /* ************** *
     * API TEST CASES
     * ************** */

    /**
     * @testCase     Construction with default Guzzle client
     * @dataProvider dataProviderForHttp
     * @param        bool $useHttps
     */
    public function testConstructionWithDefaultClient(bool $useHttps)
    {
        // When
        $api = new Numverify\Api(self::ACCESS_KEY, $useHttps);

        // Then
        $this->doesNotPerformAssertions();
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
