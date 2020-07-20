<?php

namespace ApiQueryParser\Params;

interface RequestParamsInterface
{
    public function hasFilter(): bool;

    public function getFilters(): array;

    public function hasLocation(): bool;

    public function getLocation(): ?LocationInterface;

    public function hasSort(): bool;

    public function getSorts(): array;

    public function hasPagination(): bool;

    public function getPagination(): ?PaginationInterface;

    public function hasConnection(): bool;

    public function getConnections(): array;

    public function addFilter(FilterInterface $filter): void;

    public function addLocation(LocationInterface $location): void;

    public function addSort(SortInterface $sort): void;

    public function addPagination(PaginationInterface $pagination): void;

    public function addConnection(ConnectionInterface $connection): void;
}
