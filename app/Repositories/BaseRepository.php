<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\BaseRepositoryContract;
use App\Traits\HandleFiles;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

/**
 * Abstract base repository class.
 */
abstract class BaseRepository implements BaseRepositoryContract
{
    use HandleFiles;
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model The Eloquent model.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve paginated records of a model with optional filtering and eager loading of relations.
     *
     * @param array $data Additional data for the query.
     * @param array|null $withRelations Additional array to load relations if required.
     * @return LengthAwarePaginator The paginated collection of model records.
     */
    abstract public function index(array $data, ?array $withRelations = []): LengthAwarePaginator;

    /**
     * Apply a filter to the query builder.
     *
     * @param Builder $query The query builder instance.
     * @param string $filter The filter name.
     * @param mixed $value The filter value.
     * @param mixed|null $allFilters Additional filters to be applied.
     * @return void
     */
    abstract public function applyFilter(Builder &$query, string $filter, $value, $allFilters = null): void;

    /**
     * Retrieve all records of a model with optional eager loading of relations.
     *
     * @param array $data Additional data for the query.
     * @param array|null $withRelations Additional array to load relations if required.
     * @return Collection The collection of model records.
     */
    public function all(array $data, ?array $withRelations = []): Collection
    {
        return $this->model->newQuery()->with($withRelations)->get();
    }

    /**
     * Retrieve a model by its primary key with optional eager loading of relations.
     *
     * @param int $id The primary key.
     * @param array|null $withRelations Additional array to load relations if required.
     * @return Model|null The model if found, null otherwise.
     */
    public function show(int $id, ?array $withRelations = []): ?Model
    {
        return $this->model->with($withRelations)->findOrFail($id);
    }

    /**
     * Store a new record in the database.
     *
     * @param array $data Data to create a new record.
     * @return Model The newly created model.
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update the specified record in the database.
     *
     * @param int $id The primary key.
     * @param array $data Data to update the record.
     * @return Model The updated model.
     */
    public function update(int $id, array $data): Model
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record;
    }

    /**
     * Delete the specified record from the database.
     *
     * @param int $id The primary key.
     * @return bool|null True on success, null otherwise.
     */
    public function destroy(int $id): ?bool
    {
        $model = $this->show($id);
        $files = $this->getFilesArray($model->file);
        $this->unlinkFiles($files);
        return $model->delete();
    }

    protected function getFilesArray($filePaths): array
    {
        return is_array($filePaths) ? $filePaths : explode(',', $filePaths);
    }

    /**
     * Get the attributes of the model without the id, created_at, and updated_at fields.
     *
     * @return array The attribute names of the model.
     */
    public function getModelAttributes(): array
    {
        return array_diff(Schema::getColumnListing($this->model->getTable()), ['id', 'created_at', 'updated_at']);
    }

    /**
     * Apply conditional WHERE clauses to a query builder.
     *
     * This method supports standard equality checks, 'LIKE' queries,
     * and array-based 'whereIn' conditions. For 'LIKE' queries,
     * it automatically wraps the value with wildcards. If an array
     * of values is provided, it creates a 'whereIn' condition for
     * equality checks or iterates over the array for 'LIKE' queries.
     *
     * @param Builder $query The query builder instance to apply conditions on.
     * @param string $column The column name to apply the condition on.
     * @param mixed $value The value for the condition. Can be a single value or an array.
     * @param string $operator The operator for the condition. Defaults to '='.
     *                        For 'LIKE' queries, it wraps single values with wildcards.
     *                        For arrays with 'LIKE', it applies a 'LIKE' query for each item.
     * @return void
     */
    public function applyConditionalWhere(Builder $query, string $column, $value, string $operator = '='): void
    {
        if ($operator === 'LIKE') {
            if (!is_array($value)) {
                $value = '%' . $value . '%';
                $query->where($column, 'LIKE', $value);
            } else {
                $query->where(function ($subQuery) use ($column, $value) {
                    foreach ($value as $item) {
                        $subQuery->orWhere($column, 'LIKE', '%' . $item . '%');
                    }
                });
            }
        } else {
            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $operator, $value);
            }
        }
    }
}
