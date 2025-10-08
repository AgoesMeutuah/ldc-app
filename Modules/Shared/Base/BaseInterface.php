<?php

namespace Modules\Shared\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseInterface
{
    public function all(array $columns = ['*']): Collection;
    public function find($id, array $columns = ['*']): ?Model;
    public function findOrFail($id, array $columns = ['*']): Model;
    public function findByField(string $field, $value, array $columns = ['*']): ?Model;
    public function getByField(string $field, $value, array $columns = ['*']): Collection;
    public function create(array $attributes): Model;
    public function update($id, array $attributes): Model;
    public function delete($id): bool;
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;
    public function count(): int;
}
