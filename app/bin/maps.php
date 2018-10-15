#!/usr/bin/env php
<?php
declare(strict_types=1);

use BreiteSeite\CodingChallenge\SynergistIO\Application\Command\ResolveAddressCommand;

require __DIR__ . '/../vendor/autoload.php';

const EXIT_UNKNOWN_ERROR = -1;
const EXIT_CANNOT_OPEN_STREAMS = -2;

// use anonymous function to prevent polluting global scope
$exitCode = (function (array $cliArguments): int
{
    $stdout = fopen('php://stdout', 'wb+');
    try {
        $stderr = fopen('php://stderr', 'wb+');
        try {
            if (false === $stdout || false === $stderr) {
                echo 'Could not open stdout/stderr stream';
                return EXIT_CANNOT_OPEN_STREAMS;
            }

            $resolveAddressCommand = new ResolveAddressCommand($stdout, $stderr);
            return $resolveAddressCommand->execute($cliArguments);
        } finally {
            fclose($stderr);
        }
    } finally {
        fclose($stdout);
    }

    return EXIT_UNKNOWN_ERROR;
})($argv);

exit($exitCode);
