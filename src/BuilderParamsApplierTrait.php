<?php

namespace ApiQueryParser;

use ApiQueryParser\Params\LocationInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use ApiQueryParser\Params\Filter;
use ApiQueryParser\Params\RequestParamsInterface;
use ApiQueryParser\Params\Sort;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait BuilderParamsApplierTrait
{
    public function applyParams(Builder $query, RequestParamsInterface $params): Paginator
    {
        if ($params->hasFilter()) {
            foreach ($params->getFilters() as $filter) {
                $this->applyFilter($query, $filter);
            }
        }

        if ($params->hasLocation()) {
            $this->applyLocation($query, $params->getLocation());
        }

        if ($params->hasSort()) {
            foreach ($params->getSorts() as $sort) {
                $this->applySort($query, $sort);
            }
        }

        if ($params->hasConnection()) {
            $with = [];
            foreach ($params->getConnections() as $connection) {
                $with[] = $connection->getName();
            }
            $query->with($with);
        }

        if ($params->hasPagination()) {
            $pagination = $params->getPagination();
            $query->limit($pagination->getLimit());
            $query->offset($pagination->getPage() * $pagination->getLimit());

            if (!$params->hasLocation()) {
                $paginator = $query->paginate(
                    $params->getPagination()->getLimit(),
                    ['*'],
                    'page',
                    $params->getPagination()->getPage()
                );
            } else {

                $paginator = $query->simplePaginate(
                    $params->getPagination()->getLimit()
                );
            }
        } else {
            $paginator = $query->simplePaginate();
        }

        return $paginator;
    }

    protected function applyFilter(Builder $query, Filter $filter): void
    {
        $table = $query->getModel()->getTable();
        $field = sprintf('%s.%s', $table, $filter->getField());
        $operator = $filter->getOperator();
        $value = $filter->getValue();
        $method = 'where';
        $clauseOperator = null;

        switch ($operator) {
            case 'ct':
                $value = '%' . $value . '%';
                $clauseOperator = 'LIKE';
                break;
            case 'nct':
                $value = '%' . $value . '%';
                $clauseOperator = 'NOT LIKE';
                break;
            case 'sw':
                $value .= '%';
                $clauseOperator = 'LIKE';
                break;
            case 'ew':
                $value = '%' . $value;
                $clauseOperator = 'LIKE';
                break;
            case 'eq':
                $clauseOperator = '=';
                break;
            case 'ne':
                $clauseOperator = '!=';
                break;
            case 'gt':
                $clauseOperator = '>';
                break;
            case 'ge':
                $clauseOperator = '>=';
                break;
            case 'lt':
                $clauseOperator = '<';
                break;
            case 'le':
                $clauseOperator = '<=';
                break;
            default:
                throw new BadRequestHttpException(sprintf('Not allowed operator: %s', $operator));
        }

        if ($operator === 'in') {
            $query->whereIn($filter, explode('|', $value));
        } else {
            call_user_func_array(
                [$query, $method],
                [$field, $clauseOperator, $value]
            );
        }
    }

    protected function applyLocation(Builder $query, LocationInterface $location)
    {
        $query->selectRaw(
            '*, (
				6371 * ACOS(
					COS( RADIANS('.$location->getLatitudeField().') ) *
					COS( RADIANS('.$location->getLatitudeValue().') ) *
					COS( RADIANS('.$location->getLongitudeValue().') - RADIANS('.$location->getLongitudeField().') ) +
					SIN( RADIANS('.$location->getLatitudeField().') ) * SIN( RADIANS('.$location->getLatitudeValue().') )
				)
			) distance'
        )->having('distance', '<=', $location->getRadiusValue());
    }

    protected function applySort(Builder $query, Sort $sort)
    {
        $query->orderBy($sort->getField(), $sort->getDirection());
    }
}
