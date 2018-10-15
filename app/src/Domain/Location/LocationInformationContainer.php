<?php
declare(strict_types=1);

namespace BreiteSeite\CodingChallenge\SynergistIO\Domain\Location;

use BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\LocationInformation as LocationInformationInterface;

final class LocationInformationContainer implements LocationInformationInterface
{
    private $country;
    private $state;
    private $county;
    private $city;
    private $streetAddress;
    private $streetNumber;
    private $route;
    private $postCode;

    /**
     * AddressInformation constructor.
     * @param $country
     * @param $state
     * @param $county
     * @param $city
     * @param $streetAddress
     * @param $streetNumber
     * @param $route
     */
    public function __construct($country, $state, $county, $city, $streetAddress, $streetNumber, $route, $postCode)
    {
        $this->country = $country;
        $this->state = $state;
        $this->county = $county;
        $this->city = $city;
        $this->streetAddress = $streetAddress;
        $this->streetNumber = $streetNumber;
        $this->route = $route;
        $this->postCode = $postCode;
    }

    public function country(): ?string
    {
        return $this->country;
    }

    public function state(): ?string
    {
        return $this->state;
    }

    public function county(): ?string
    {
        return $this->county;
    }

    public function city(): ?string
    {
        return $this->city;
    }

    public function streetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function streetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function route(): ?string
    {
        return $this->route;
    }

    public function postCode(): ?string
    {
        return $this->postCode;
    }
}
