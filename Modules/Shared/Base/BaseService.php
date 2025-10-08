<?php

namespace Modules\Shared\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseService
{
    /**
     * @var BaseRepository
     */
    protected BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Ambil semua data
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    /**
     * Ambil satu data berdasarkan ID
     */
    public function find($id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * Simpan data baru
     */
    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    /**
     * Update data
     */
    public function update($id, array $data): Model
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Hapus data
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Paginasi
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * Hitung total data
     */
    public function count(): int
    {
        return $this->repository->count();
    }
}
