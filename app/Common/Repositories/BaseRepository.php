<?php

namespace App\Common\Repositories;

use App\Common\Filters\QueryFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    protected array $allowedFilters = [];

    protected array $searchable = [];

    protected array $allowedSorts = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get a fresh query builder.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Get all records.
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()->get();
    }

    /**
     * Find a record by ID.
     */
    public function find(int|string $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Create a new record.
     */
    public function create(array $data): Model
    {
        return $this->query()->create($data);
    }

    /**
     * Update an existing record.
     */
    public function update(int|string $id, array $data): Model
    {
        $record = $this->find($id);

        $record->update($data);

        return $record->refresh();
    }

    /**
     * Delete a record.
     */
    public function delete(int|string $id): bool
    {
        return (bool) $this->find($id)->delete();
    }

    /**
     * Get paginated and filtered records.
     */
    public function paginate(
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        return app(QueryFilter::class)->apply(
            query: $this->query(),
            filters: $filters,
            allowedFilters: $this->allowedFilters,
            searchable: $this->searchable,
            allowedSorts: $this->allowedSorts,
            defaultPerPage: $perPage
        );
    }
}