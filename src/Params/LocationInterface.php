<?php

namespace ApiQueryParser\Params;

interface LocationInterface
{
    public function getLatitudeField(): string;

    public function getLongitudeField(): string;

    public function getLatitudeValue(): float;

    public function getLongitudeValue(): float;

    public function getRadiusValue(): float;

    public function getJoinDefinition(): ?string;
}
