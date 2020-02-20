<?php

namespace ApiQueryParser;

use Illuminate\Http\Request;
use ApiQueryParser\Params\RequestParamsInterface;

trait ResourceQueryParserTrait
{
    protected function parseQueryParams(Request $request): RequestParamsInterface
    {
        $parser = app(RequestQueryParserInterface::class);

        return $parser->parse($request);
    }
}
