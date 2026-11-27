<?php

namespace App\Common\Filters;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class QueryFilter
{
    /**
     * Apply filtering, searching, sorting and pagination.
     */
    public function apply(
        Builder $query,
        array $filters,
        array $allowedFilters = [],
        array $searchable = [],
        array $allowedSorts = [],
        int $defaultPerPage = 15
    ): LengthAwarePaginator {

        // Apply exact-match filters.
        foreach ($allowedFilters as $field) {
            $value = data_get($filters, "filters.{$field}");

            if ($value !== null && $value !== '') {
                $query->where($field, $value);
            }
        }

        // Apply search.
        $search = $filters['search'] ?? null;

        if ($search && !empty($searchable)) {
            $query->where(function (Builder $builder) use (
                $search,
                $searchable
            ) {
                foreach ($searchable as $column) {
                    $builder->orWhere(
                        $column,
                        'LIKE',
                        "%{$search}%"
                    );
                }
            });
        }

        // Apply sorting.
        $sortBy = $filters['sort_by'] ?? null;

        if ($sortBy && in_array($sortBy, $allowedSorts, true)) {
            $sortDirection = strtolower(
                $filters['sort_dir'] ?? 'asc'
            );

            $sortDirection = in_array(
                $sortDirection,
                ['asc', 'desc'],
                true
            ) ? $sortDirection : 'asc';

            $query->orderBy($sortBy, $sortDirection);
        }

        // Apply pagination.
        $perPage = (int) ($filters['per_page'] ?? $defaultPerPage);

        $perPage = max(1, min($perPage, 100));

        return $query->paginate($perPage)->withQueryString();
    }
}
