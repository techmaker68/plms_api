<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface for base service operations.
 */
interface BaseServiceContract
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
     * Prepare filters from the provided data.
     *
     * @param array $data
     * @return array
     */
    public function prepareFilters(array $data): array;

    /**
     * Prepare data for storage based on model attributes.
     *
     * @param array $data
     * @return array
     */
    public function prepareDataForStorage(array $data): array;
}