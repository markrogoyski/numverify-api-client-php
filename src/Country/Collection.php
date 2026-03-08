<?php

namespace Numverify\Country;

/**
 * Country Collection
 * Role: Collection of callable countries
 * @implements \Iterator<Country>
 */
class Collection implements \Iterator, \Countable, \JsonSerializable
{
    /** @var Country[] */
    private array $countriesByCountryCode = [];

    /** @var Country[] */
    private array $countriesByName = [];

    /**
     * Collection constructor
     */
    public function __construct(Country ...$countries)
    {
        foreach ($countries as $country) {
            $this->countriesByCountryCode[$country->getCountryCode()] = $country;
            $this->countriesByName[$country->getCountryName()]        = $country;
        }
    }

    /**
     * Find country by country code
     */
    public function findByCountryCode(string $countryCode): ?Country
    {
        return $this->countriesByCountryCode[$countryCode] ?? null;
    }

    /**
     * Find country by name
     */
    public function findByCountryName(string $countryName): ?Country
    {
        return $this->countriesByName[$countryName] ?? null;
    }

    /**
     * Countable interface
     */
    public function count(): int
    {
        return \count($this->countriesByCountryCode);
    }

    /**
     * JsonSerializable interface
     *
     * @return object[]
     */
    public function jsonSerialize(): array
    {
        return $this->countriesByCountryCode;
    }

    /**
     * Iterator interface
     */
    public function rewind(): void
    {
        \reset($this->countriesByCountryCode);
    }

    /**
     * Iterator interface
     */
    public function current(): Country
    {
        $country = \current($this->countriesByCountryCode);
        if ($country === false) {
            throw new \LogicException('Iteration error - current returned false');
        }
        return $country;
    }

    /**
     * Iterator interface
     */
    public function key(): mixed
    {
        return \key($this->countriesByCountryCode);
    }

    /**
     * Iterator interface
     */
    public function next(): void
    {
        \next($this->countriesByCountryCode);
    }

    /**
     * Iterator interface
     */
    public function valid(): bool
    {
        return \key($this->countriesByCountryCode) !== null;
    }
}
