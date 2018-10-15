#!/usr/bin/env php
<?php
declare(strict_types=1);


use BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\Resolver\Google\Geocode;
use BreiteSeite\CodingChallenge\SynergistIO\Infrastructure\Google\Geocode\Client\JsonClient;

error_reporting(-1);

require __DIR__ . '/../vendor/autoload.php';

const ENV_VAR_GOOGLE_GEOCODING_API_KEY = 'APIKEY_GOOGLE_GEOCODING';
const EXIT_SUCCESS = 0;
const EXIT_MISSING_ENV_CONFIG = 1;
const EXIT_INVALID_USAGE = 2;

$exitCode = (function (array $cliArguments): int {
    $printUsage = function (): int {
        echo 'Usage: maps.php <address>';
        echo PHP_EOL;

        return EXIT_INVALID_USAGE;
    };

    if (false === getenv(ENV_VAR_GOOGLE_GEOCODING_API_KEY)) {
        echo 'Set env "APIKEY_GOOGLE_GEOCODING" with your API key to use this program';
        echo PHP_EOL;

        return EXIT_MISSING_ENV_CONFIG;
    }

    if (!isset($cliArguments[1])) {
        return $printUsage();
    }

    if (count($cliArguments) > 2) {
        return $printUsage();
    }

    $addressInput = $cliArguments[1];

    $jsonClient = new JsonClient(getenv(ENV_VAR_GOOGLE_GEOCODING_API_KEY));
    $geocode = new Geocode($jsonClient);


    var_dump($geocode->resolveLocation($addressInput));

    return EXIT_SUCCESS;
})($argv);

exit($exitCode);
