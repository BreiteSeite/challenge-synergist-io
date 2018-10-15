<?php
declare(strict_types=1);

namespace BreiteSeite\CodingChallenge\SynergistIO\Application\Command;

use BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\Resolver\Google\Geocode;
use BreiteSeite\CodingChallenge\SynergistIO\Infrastructure\Google\Geocode\Client\JsonClient;

final class ResolveAddressCommand
{
    private const ENV_VAR_GOOGLE_GEOCODING_API_KEY = 'APIKEY_GOOGLE_GEOCODING';
    private const EXIT_SUCCESS = 0;
    private const EXIT_MISSING_ENV_CONFIG = 1;
    private const EXIT_INVALID_USAGE = 2;

    /** @var resource */
    private $stdout;

    /** @var resource */
    private $stderr;

    /**
     * ResolveAddressCommand constructor.
     * @param resource $stdout
     * @param resource $stderr
     */
    public function __construct($stdout, $stderr)
    {
        if (!\is_resource($stdout)) {
            throw new \RuntimeException('stdout is not a resource');
        }

        if (!\is_resource($stderr)) {
            throw new \RuntimeException('sterr is not a resource');
        }

        $this->stdout = $stdout;
        $this->stderr = $stderr;
    }


    public function execute(array $cliArguments): int
    {
        if (false === getenv(self::ENV_VAR_GOOGLE_GEOCODING_API_KEY)) {
            $this->writeLnStderr('Set env "APIKEY_GOOGLE_GEOCODING" with your API key to use this program');

            return self::EXIT_MISSING_ENV_CONFIG;
        }


        if (!isset($cliArguments[1])) {
            $this->printUsage();

            return self::EXIT_INVALID_USAGE;
        }

        if (\count($cliArguments) > 2) {
            $this->printUsage();

            return self::EXIT_INVALID_USAGE;
        }

        $addressInput = $cliArguments[1];

        $jsonClient = new JsonClient(getenv(self::ENV_VAR_GOOGLE_GEOCODING_API_KEY));
        $geocode = new Geocode($jsonClient);


        $locations = $geocode->resolveLocation($addressInput);
        $this->writeLnStdout(sprintf('%d locations found', \count($locations)));

        foreach ($locations as $locationPosition => $location) {
            $this->writeLnStdout(sprintf('######### Location %d:', $locationPosition));
            $this->writeLnStdout(PHP_EOL);

            $this->writeLnStdout(sprintf('Country: %s', $location->country() ?? 'N/A'));
            $this->writeLnStdout(sprintf('State: %s', $location->state() ?? 'N/A'));
            $this->writeLnStdout(sprintf('County: %s', $location->country() ?? 'N/A'));
            $this->writeLnStdout(sprintf('Zip: %s', $location->postCode() ?? 'N/A'));
            $this->writeLnStdout(sprintf('City: %s', $location->city() ?? 'N/A'));
            $this->writeLnStdout(sprintf('Route: %s', $location->route() ?? 'N/A'));
            $this->writeLnStdout(sprintf('Street number: %s', $location->streetNumber() ?? 'N/A'));
            $this->writeLnStdout(sprintf('Street address: %s', $location->streetAddress() ?? 'N/A'));
        }

        return self::EXIT_SUCCESS;
    }

    private function printUsage(): void
    {
        $this->writeLnStderr('Usage: maps.php <address>');
    }

    private function writeLnStdout(string $text): void
    {
        fwrite($this->stdout, $text);
        fwrite($this->stdout, PHP_EOL);
    }

    private function writeLnStderr(string $text): void
    {
        fwrite($this->stderr, $text);
        fwrite($this->stderr, PHP_EOL);

    }
}
