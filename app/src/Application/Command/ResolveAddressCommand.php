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
            $this->writeStderr('Set env "APIKEY_GOOGLE_GEOCODING" with your API key to use this program');

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
        $this->writeStdout(sprintf('%d locations found', \count($locations)));

        foreach ($locations as $locationPosition => $location) {
            $this->writeStdout(sprintf('######### Location %d:', $locationPosition));
            $this->writeStdout(PHP_EOL);

            $this->writeStdout(sprintf('Country: %s', $location->country() ?? 'N/A'));
            $this->writeStdout(sprintf('State: %s', $location->state() ?? 'N/A'));
            $this->writeStdout(sprintf('County: %s', $location->country() ?? 'N/A'));
            $this->writeStdout(sprintf('Zip: %s', $location->postCode() ?? 'N/A'));
            $this->writeStdout(sprintf('City: %s', $location->city() ?? 'N/A'));
            $this->writeStdout(sprintf('Route: %s', $location->route() ?? 'N/A'));
            $this->writeStdout(sprintf('Street number: %s', $location->streetNumber() ?? 'N/A'));
            $this->writeStdout(sprintf('Street address: %s', $location->streetAddress() ?? 'N/A'));
        }

        return self::EXIT_SUCCESS;
    }

    private function printUsage(): void
    {
        $this->writeStderr('Usage: maps.php <address>');
    }

    private function writeStdout(string $text): void
    {
        fwrite($this->stdout, $text);
        fwrite($this->stdout, PHP_EOL);
    }

    private function writeStderr(string $text): void
    {
        fwrite($this->stderr, $text);
        fwrite($this->stderr, PHP_EOL);

    }
}
