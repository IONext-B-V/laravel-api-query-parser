<?php

namespace ApiQueryParser;

use Illuminate\Http\Request;
use ApiQueryParser\Params\RequestParamsInterface;

interface RequestQueryParserInterface
{
    public function parse(Request $request): RequestParamsInterface;
}
