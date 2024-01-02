<?php

namespace App\Services;

use App\Contracts\BaseServiceContract;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Abstract base service class.
 */
abstract class BaseService implements BaseServiceContract
{
    protected BaseRepositoryContract $repository;

    /**
     * BaseService constructor.
     *
     * @param BaseRepositoryContract $repository
     */
    public function __construct(BaseRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all models with optional relations.
     *
     * @param array $data
     * @param array|null $withRelations
     * @return Collection
     */
    public function all(array $data, ?array $withRelations = []): Collection {
        return $this->repository->all($data, $withRelations);
    }

    /**
     * Get paginated models with optional relations.
     *
     * @param array $data
     * @param array|null $withRelations
     * @return LengthAwarePaginator
     */
    public function index(array $data, ?array $withRelations = []): LengthAwarePaginator {
        return $this->repository->index($data, $withRelations);
    }

    /**
     * Store a new model.
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model {
        return $this->repository->store($data);
    }

    /**
     * Get a model by ID with optional relations.
     *
     * @param int $id
     * @param array|null $withRelations
     * @return Model|null
     */
    public function show(int $id, ?array $withRelations = []): ?Model {
        return $this->repository->show($id, $withRelations);
    }

    /**
     * Update a model by ID.
     *
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data): Model {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a model by ID.
     *
     * @param int $id
     * @return bool|null
     */
    public function destroy(int $id): ?bool {
        return $this->repository->destroy($id);
    }

    /**
     * Prepare filters from the provided data.
     *
     * @param array $validatedRequest
     * @return array
     */
    public function prepareFilters(array $validatedRequest): array {
        $filters = [];

        foreach ($validatedRequest as $key => $value) {
            $filters[$key] = $this->applyFilter($value);
        }

        $filters['sort_by'] = $filters['sort_by'] ?? 'id';
        $filters['order'] = $filters['order'] ?? 'desc';
        $filters['method'] = isset($filters['all']) && $filters['all'] ? 'all' : 'index';

        return $filters;
    }

    /**
     * Apply specific filter based on type.
     *
     * @param mixed $value
     * @return mixed
     */
    private function applyFilter($value) {
        if (is_array($value)) {
            return array_map('trim', $value);
        } elseif ($this->isBoolean($value)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        } elseif ($this->isCommaSeparatedString($value)) {
            return array_map('trim', explode(',', $value));
        } elseif (is_numeric($value)) {
            return $value + 0;
        } else {
            return trim($value);
        }
    }

    /**
     * Check if the value is a boolean.
     *
     * @param mixed $value
     * @return bool
     */
    private function isBoolean($value): bool {
        return in_array(strtolower($value), [true, false, 'true', 'false', 'yes', 'no'], true);
    }

    /**
     * Check if the value is a comma-separated string.
     *
     * @param mixed $value
     * @return bool
     */
    private function isCommaSeparatedString($value): bool {
        return is_string($value) && strpos($value, ',') !== false;
    }

    /**
     * Prepare data for storage based on model attributes.
     *
     * @param array $validatedData
     * @return array
     */
    public function prepareDataForStorage(array $validatedData) : array
    { 
        return array_intersect_key($validatedData, array_flip($this->repository->getModelAttributes()));
    }
}
