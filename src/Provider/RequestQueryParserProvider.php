<?php

namespace ApiQueryParser\Provider;

use Illuminate\Support\ServiceProvider;
use ApiQueryParser\RequestQueryParser;
use ApiQueryParser\RequestQueryParserInterface;

class RequestQueryParserProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RequestQueryParserInterface::class, function () {
            return new RequestQueryParser();
        });
    }
}
