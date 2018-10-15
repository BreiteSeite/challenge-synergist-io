<?php
declare(strict_types=1);

namespace BreiteSeite\CodingChallenge\SynergistIO\Infrastructure\Google\Geocode\Client;

class JsonClient
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    /** @var string */
    private $apiKey;

    /**
     * JsonClient constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $address
     * @return array
     * @throws RuntimeException if the response contains no status
     * @throws RuntimeException if the response contains a status indicating an error
     */
    public function sendRequest(string $address): array
    {
        $queryComponents = [
            'address' => $address,
            'key' => $this->apiKey,
        ];
        $queryString = http_build_query($queryComponents);
        $url = self::API_URL . '?' . $queryString;

        $response = json_decode(file_get_contents($url), true);

        if (isset($response['status'])) {
            switch ($response['status']) {
                case 'OK':
                    return $response['results'];
                case 'ZERO_RESULTS':
                    return [];
                default:
                    throw new RuntimeException(sprintf('Response contains invalid status: %s', $response['status']));
            }
        }

        if (isset($response['results'])) {
            return $response['results'];
        }

        throw new RuntimeException('Invalid response (missing status)');
    }
}
