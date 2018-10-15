<?php
declare(strict_types=1);

use BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\Resolver\Google\Geocode;

final class GeocodeTest extends \PHPUnit\Framework\TestCase
{
    private $client;

    public function testClientReturnsStreet()
    {
        $this->client->sendRequest('')->willReturn([
            [
                'address_components' => [
                    [],
                    [
                        'long_name' => 'PHPUnit test street',
                        'types' => ['street_address'],
                    ],
                    [],
                ],
            ]
        ]);

        $geocode = new Geocode($this->client->reveal());

        $this->assertSame('PHPUnit test street', $geocode->resolveLocation('')[0]->streetAddress());
    }

    protected function setUp()
    {
        $this->client = $this->prophesize(Geocode\Client\JsonClient::class);
    }
}
