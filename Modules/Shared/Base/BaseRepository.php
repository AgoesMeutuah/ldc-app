<?php

namespace Modules\Shared\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var Builder
     */
    protected Builder $query;

    /**
     * @var array
     */
    protected array $with = [];

    public function __construct()
    {
        $this->makeModel();
        $this->resetQuery();
    }

    /**
     * Specify Model class name
     */
    abstract protected function model(): string;

    /**
     * Make model instance
     */
    protected function makeModel(): void
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new \RuntimeException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        $this->model = $model;
    }

    /**
     * Reset query builder
     */
    protected function resetQuery(): self
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    /**
     * Get all records
     */
    public function all(array $columns = ['*']): Collection
    {
        $results = $this->query->with($this->with)->get($columns);
        $this->resetQuery();
        return $results;
    }

    /**
     * Find record by ID
     */
    public function find($id, array $columns = ['*']): ?Model
    {
        $result = $this->query->with($this->with)->find($id, $columns);
        $this->resetQuery();
        return $result;
    }

    /**
     * Find or fail record by ID
     */
    public function findOrFail($id, array $columns = ['*']): Model
    {
        $result = $this->query->with($this->with)->findOrFail($id, $columns);
        $this->resetQuery();
        return $result;
    }

    /**
     * Find record by field
     */
    public function findByField(string $field, $value, array $columns = ['*']): ?Model
    {
        $result = $this->query->with($this->with)->where($field, $value)->first($columns);
        $this->resetQuery();
        return $result;
    }

    /**
     * Pluck record by field
     */
    public function pluckByField(string $column, $id = 'id'): array
    {
        $result = $this->query->with($this->with)->pluck($column, $id)->toArray();
        $this->resetQuery();
        return $result;
    }

    /**
     * Get records by field
     */
    public function getByField(string $field, $value, array $columns = ['*']): Collection
    {
        $results = $this->query->with($this->with)->where($field, $value)->get($columns);
        $this->resetQuery();
        return $results;
    }

    /**
     * Create new record
     */
    public function create(array $attributes): Model
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        return $model->fresh();
    }

    /**
     * Update record
     */
    public function update($id, array $attributes): Model
    {
        $model = $this->findOrFail($id);
        $model->fill($attributes);
        $model->save();
        return $model->fresh();
    }

    /**
     * Delete record
     */
    public function delete($id): bool
    {
        $model = $this->findOrFail($id);
        return $model->delete();
    }

    /**
     * Paginate records
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        $results = $this->query->with($this->with)->paginate($perPage, $columns);
        $this->resetQuery();
        return $results;
    }

    /**
     * Count records
     */
    public function count(): int
    {
        $count = $this->query->count();
        $this->resetQuery();
        return $count;
    }

    /**
     * With relationships
     */
    public function with(array $relations): self
    {
        $this->with = array_merge($this->with, $relations);
        return $this;
    }

    /**
     * Where conditions
     */
    public function where(array $conditions): self
    {
        $this->query->where($conditions);
        return $this;
    }

    /**
     * Order by clause
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    /**
     * Eager load relationships
     */
    public function withRelations(array $relations): self
    {
        $this->with = $relations;
        return $this;
    }

    /**
     * Where like clause
     */
    public function whereLike(string $column, string $value): self
    {
        $this->query->where($column, 'LIKE', "%{$value}%");
        return $this;
    }

    /**
     * Where in clause
     */
    public function whereIn(string $column, array $values): self
    {
        $this->query->whereIn($column, $values);
        return $this;
    }

    /**
     * Where between clause
     */
    public function whereBetween(string $column, array $values): self
    {
        $this->query->whereBetween($column, $values);
        return $this;
    }

    /**
     * First or create record
     */
    public function firstOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    /**
     * Update or create record
     */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
