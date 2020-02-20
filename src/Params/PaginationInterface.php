<?php

namespace ApiQueryParser\Params;

interface PaginationInterface
{
    public function getLimit(): ?int;

    public function getPage(): ?int;
}
