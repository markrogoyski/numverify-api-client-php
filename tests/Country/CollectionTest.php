<?php

namespace Numverify\Tests;

use Numverify\Country\Country;
use Numverify\Country\Collection;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase findByCountryCode
     */
    public function testFindByCountryCode()
    {
        // Given
        $collection = new Collection(...[$this->countryUs, $this->countryGb, $this->countryJp]);

        // When
        $countryUs = $collection->findByCountryCode($this->countryUs->getCountryCode());
        $countryGb = $collection->findByCountryCode($this->countryGb->getCountryCode());
        $countryJp = $collection->findByCountryCode($this->countryJp->getCountryCode());

        // Then
        $this->assertEquals($this->countryUs, $countryUs);
        $this->assertEquals($this->countryGb, $countryGb);
        $this->assertEquals($this->countryJp, $countryJp);
    }

    /**
     * @testCase findByCountryName
     */
    public function testFindByCountryName()
    {
        // Given
        $collection = new Collection(...[$this->countryUs, $this->countryGb, $this->countryJp]);

        // When
        $countryUs = $collection->findByCountryName($this->countryUs->getCountryName());
        $countryGb = $collection->findByCountryName($this->countryGb->getCountryName());
        $countryJp = $collection->findByCountryName($this->countryJp->getCountryName());

        // Then
        $this->assertEquals($this->countryUs, $countryUs);
        $this->assertEquals($this->countryGb, $countryGb);
        $this->assertEquals($this->countryJp, $countryJp);
    }

    /**
     * @testCase     Countable interface
     * @dataProvider dataProviderForCountryCounts
     * @param        array $countries
     * @param        int   $expectedCount
     */
    public function testCount(array $countries, int $expectedCount)
    {
        // Given
        $collection = new Collection(...$countries);

        // Then
        $this->assertCount($expectedCount, $collection);
    }

    /**
     * @return array
     */
    public function dataProviderForCountryCounts(): array
    {
        return [
            'zero' => [
                [],
                0,
            ],
            'one' => [
                [new Country('US', 'United States', '+1')],
                1,
            ],
            'two' => [
                [
                    new Country('US', 'United States', '+1'),
                    new Country('GB', 'United Kingdom', '+44')
                ],
                2,
            ],
            'three' => [
                [
                    new Country('US', 'United States', '+1'),
                    new Country('GB', 'United Kingdom', '+44'),
                    new Country('JP', 'Japan', '+81')
                ],
                3,
            ],
        ];
    }

    /**
     * @testCase JsonSerialize interface
     */
    public function testJsonSerialize()
    {
        // Given
        $collection = new Collection(...[$this->countryUs, $this->countryGb, $this->countryJp]);

        // When
        $json = json_encode($collection);

        // Then
        $object = json_decode($json);
        $this->assertObjectHasAttribute('US', $object);
        $this->assertObjectHasAttribute('GB', $object);
        $this->assertObjectHasAttribute('JP', $object);

        // And
        $this->assertEquals('US', $object->US->countryCode);
        $this->assertEquals('GB', $object->GB->countryCode);
        $this->assertEquals('JP', $object->JP->countryCode);
        $this->assertEquals('United States', $object->US->countryName);
        $this->assertEquals('United Kingdom', $object->GB->countryName);
        $this->assertEquals('Japan', $object->JP->countryName);
        $this->assertEquals('+1', $object->US->diallingCode);
        $this->assertEquals('+44', $object->GB->diallingCode);
        $this->assertEquals('+81', $object->JP->diallingCode);
    }

    /**
     * @testCase Iterator interface
     */
    public function testIterator()
    {
        // Given
        $collection        = new Collection(...[$this->countryUs, $this->countryGb, $this->countryJp]);
        $expectedCountries = ['US' => false, 'GB' => false, 'JP' => false];

        // When
        foreach ($collection as $countryCode => $country) {
            $expectedCountries[$countryCode] = true;
            $this->assertInstanceOf(Country::class, $country);
        }

        // Then
        foreach ($expectedCountries as $countryCode => $seen) {
            $this->assertTrue($seen);
        }
    }

    /**
     * @test Iteration failure if manually manipulating the iterator (with elements)
     */
    public function testIterationCurrentFailureWithElements()
    {
        // Given
        $collection = new Collection(...[$this->countryUs]);

        // And
        $collection->next();

        // Then
        $this->expectException(\LogicException::class);

        // When
        $collection->current();
    }

    /**
     * @test Iteration failure if manually manipulating the iterator (no elements)
     */
    public function testIterationCurrentFailureNoElements()
    {
        // Given
        $collection = new Collection(...[]);

        // Then
        $this->expectException(\LogicException::class);

        // When
        $collection->current();
    }

    /* ********* *
     * TEST DATA
     * ********* */

    /** @var Country */
    private $countryUs;

    /** @var Country */
    private $countryGb;

    /** @var Country */
    private $countryJp;

    /**
     * Set up countries
     */
    public function setUp(): void
    {
        $this->countryUs = new Country('US', 'United States', '+1');
        $this->countryGb = new Country('GB', 'United Kingdom', '+44');
        $this->countryJp = new Country('JP', 'Japan', '+81');
    }
}
