<?php

namespace ApiQueryParser;

use ApiQueryParser\Params\RequestParamsInterface;
use ApiQueryParser\Params\Connection;
use ApiQueryParser\Params\Filter;
use ApiQueryParser\Params\Sort;

class RequestParamParser implements RequestParamParserInterface
{
    /**
     * @var array
     */
    protected $query = [];

    public function parse(RequestParamsInterface $requestParams): string
    {
        $this->parseFilters($requestParams);
        $this->parseLocation($requestParams);
        $this->parseConnections($requestParams);
        $this->parseSort($requestParams);
        $this->parsePagination($requestParams);

        return implode('&', $this->query);
    }

    protected function parseFilters(RequestParamsInterface $params): void
    {
        $filters = [];

        /** @var Filter $filter */
        foreach ($params->getFilters() as $filter) {
            $filters[] = "filter[]={$filter->getField()}:{$filter->getOperator()}:{$filter->getValue()}";
        }

        if (empty($filters)) {
            return;
        }

        $this->query[] = implode('&', $filters);
    }

    protected function parseLocation(RequestParamsInterface $params): void
    {
        $location = $params->getLocation();

        if (is_null($location)) {
            return;
        }

        $this->query[] = "location={$location->getLatitudeField()}:{$location->getLongitudeField()}:{$location->getLatitudeValue()}:{$location->getLongitudeValue()}:{$location->getRadiusValue()}";
    }

    protected function parseSort(RequestParamsInterface $params): void
    {
        $sorts = [];

        /** @var Sort $sort */
        foreach ($params->getSorts() as $sort) {
            $sorts[] = "sort[]={$sort->getField()}:{$sort->getDirection()}";
        }

        if (empty($sorts)) {
            return;
        }

        $this->query[] = implode('&', $sorts);
    }

    protected function parsePagination(RequestParamsInterface $params): void
    {
        $pagination = $params->getPagination();

        if (is_null($pagination)) {
            return;
        }

        $limit = "limit={$pagination->getLimit()}";
        $page = "page={$pagination->getPage()}";

        $this->query[] = implode('&', [$limit, $page]);
    }

    protected function parseConnections(RequestParamsInterface $params): void
    {
        $connections = [];

        /** @var Connection $connection */
        foreach ($params->getConnections() as $connection) {
            $connections[] = "connection[]={$connection->getName()}";
        }

        if (empty($connections)) {
            return;
        }

        $this->query[] = implode('&', $connections);
    }
}
