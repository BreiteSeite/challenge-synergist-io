<?php
declare(strict_types=1);

namespace BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\Resolver\Google;

use BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\LocationInformation;
use BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\LocationInformationContainer;
use BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\Resolver;
use BreiteSeite\CodingChallenge\SynergistIO\Infrastructure\Google\Geocode\Client\JsonClient;

final class Geocode implements Resolver
{
    /** @var \BreiteSeite\CodingChallenge\SynergistIO\Infrastructure\Google\Geocode\Client\JsonClient */
    private $jsonClient;

    /**
     * Geocode constructor.
     * @param $jsonClient
     */
    public function __construct(JsonClient $jsonClient)
    {
        $this->jsonClient = $jsonClient;
    }

    /**
     * @param string $location
     * @return LocationInformation[]
     */
    public function resolveLocation(string $location): array
    {
        $clientResults = $this->jsonClient->sendRequest($location);

        $locationInformation = [];
        foreach ($clientResults as $clientResult) {
            $locationInformationContainer = $this->parseClientResult($clientResult);

            if ($locationInformationContainer instanceof LocationInformationContainer) {
                $locationInformation[] = $locationInformationContainer;
            }
        }

        return $locationInformation;
    }

    private function parseClientResult(array $clientResult): ?LocationInformationContainer
    {
        if (!isset($clientResult['address_components'])) {
            return null;
        }
        $addressComponents = $clientResult['address_components'];

        $searchComponentWithType = function (string $type, array $addressComponents): ?array {
            foreach ($addressComponents as $index => $addressComponent) {
                if (!isset($addressComponent['types'])) {
                    continue;
                }

                $key = array_search($type, $addressComponent['types'], true);

                if (false === $key) {
                    return null;
                }

                return $addressComponents[$index];
            }

            return null;
        };

        $extractLongName = function (?array $addressComponent): ?string {
            return $addressComponent['long_name'] ?? null;
        };

        return new LocationInformationContainer(
            $extractLongName($searchComponentWithType('country', $addressComponents)),
            $extractLongName($searchComponentWithType('state', $addressComponents)),
            $extractLongName($searchComponentWithType('county', $addressComponents)),
            $extractLongName($searchComponentWithType('city', $addressComponents)),
            $extractLongName($searchComponentWithType('street_address', $addressComponents)),
            $extractLongName($searchComponentWithType('street_number', $addressComponents)),
            $extractLongName($searchComponentWithType('route', $addressComponents)),
            $extractLongName($searchComponentWithType('postCode', $addressComponents))
        );
    }
}
