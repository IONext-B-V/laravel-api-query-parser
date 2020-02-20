<?php

namespace ApiQueryParser\Params;

interface SortInterface
{
    public function getField(): string;

    public function getDirection(): string;
}
