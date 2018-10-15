<?php
declare(strict_types=1);

namespace BreiteSeite\CodingChallenge\SynergistIO\Domain\Location;

interface Resolver
{
    /**
     * @param string $location location query (can be in any form)
     * @return LocationInformation[]
     */
    public function resolveLocation(string $location): array;
}
