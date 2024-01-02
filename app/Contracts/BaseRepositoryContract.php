<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryContract
{
    /**
     * Retrieve all records.
     *
     * @param array $data Additional data for the query.
     * @return Collection
     */
    public function all(array $data): Collection;

    /**
     * Retrieve a paginated list of records.
     *
     * @param array $data Data for filtering and pagination.
     * @return LengthAwarePaginator
     */
    public function index(array $data): LengthAwarePaginator;

    /**
     * Store a new record in the database.
     *
     * @param array $data Data to create a new record.
     * @return Model
     */
    public function store(array $data): Model;

    /**
     * Retrieve a record by its primary key.
     *
     * @param int $id The primary key.
     * @return Model|null
     */
    public function show(int $id): ?Model;

    /**
     * Update the specified record in the database.
     *
     * @param int $id The primary key.
     * @param array $data Data to update the record.
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete the specified record from the database.
     *
     * @param int $id The primary key.
     * @return bool|null True on success, null otherwise.
     */
    public function destroy(int $id): ?bool;

    /**
     * Apply a filter to the query.
     *
     * @param Builder $query The query builder instance.
     * @param string $filter The filter name.
     * @param mixed $value The value for the filter.
     * @param array|null $allFilters All the filters.
     * @return void
     */
    public function applyFilter(Builder &$query, string $filter, $value, ?array $allFilters = null): void;

    /**
     * Get the attributes of the model.
     *
     * @return array
     */
    public function getModelAttributes(): array;
}
