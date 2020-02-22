<?php

namespace ApiQueryParser;

use ApiQueryParser\Params\RequestParamsInterface;

interface RequestParamParserInterface
{
    public function parse(RequestParamsInterface $requestParams): string;
}
