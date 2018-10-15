<?php
declare(strict_types=1);

use BreiteSeite\CodingChallenge\SynergistIO\Domain\Location\Resolver\Google\Geocode;
use BreiteSeite\CodingChallenge\SynergistIO\Infrastructure\Google\Geocode\Client\JsonClient;

error_reporting(-1);

require __DIR__ . '/../vendor/autoload.php';

$jsonClient = new JsonClient(getenv('APIKEY_GOOGLE_GEOCODING'));
$geocode = new Geocode($jsonClient);

var_dump($geocode->resolveLocation($argv[1])); die;
