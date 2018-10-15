<?php
declare(strict_types=1);

namespace BreiteSeite\CodingChallenge\SynergistIO\Domain\Location;

interface LocationInformation
{
    public function country(): ?string;
    public function state(): ?string;
    public function county(): ?string;
    public function city(): ?string;
    public function streetAddress(): ?string;
    public function streetNumber(): ?string;
    public function postCode(): ?string;
    public function route(): ?string;
}
