<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all the data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }

    /**
     * Get all the data with order by
     *
     * @param string $orderBy
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithOrderBy($orderBy, $order = 'asc')
    {
        return $this->repository->getAllWithOrderBy($orderBy, $order);
    }

    /**
     * Get data by Id
     *
     * @param int $id
     * @return mixed(Model|Null)
     */
    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Find data by Id with relations
     *
     * @param int $id
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByIdWithRelations(int $id, array $relations = []) {
        return $this->repository->findByIdWithRelations($id, $relations);
    }

    /**
     * Get the data by condition
     *
     * @param $column
     * @param string $condition
     * @return mixed(Model|Null)
     */
    public function getByCondition(string $column, $condition)
    {
        return $this->repository->getByCondition($column, $condition);
    }

    /**
     * Get the data by multiple conditions
     *
     * @param array $args
     * @return mixed(Model|Null)
     */
    public function getByConditions(array $args = [], array $select = ['*'])
    {
        return $this->repository->getByConditions($args, $select);
    }

    /**
     * Get the data by multiple conditions with order
     *
     * @param array $args
     * @param array $select
     * @param string $orderBy
     * @param string $order
     * @return mixed(Model|Null)
     */
    public function getByConditionsWithOrderBy(array $args = [], array $select = ['*'], string $orderBy = 'id', string $order = 'asc')
    {
        return $this->repository->getByConditionsWithOrderBy($args, $select, $orderBy, $order);
    }

    /**
     * Create data by array attributes
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes = [])
    {
        return $this->repository->create($attributes);
    }

    /**
     * Insert data by array attributes
     *
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes = [])
    {
        return $this->repository->insert($attributes);
    }

    /**
     * Create data with incrementing number
     *
     * @param array $conditions
     * @param array $attributes
     * @param string $column
     * @return Model
     */
    public function createByConditionsWithIncrementedNumber(array $conditions, array $attributes, string $column)
    {
        return $this->repository->createByConditionsWithIncrementedNumber($conditions, $attributes, $column);
    }

    /**
     * Update data by Id and array attribute
     *
     * @param int $id
     * @param array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes = [])
    {
        return $this->repository->update($id, $attributes);
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param array $condition
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function updateOrCreate(array $condition = [], array $data = [])
    {
        return $this->repository->updateOrCreate($condition, $data);
    }

    /**
     * Delete data by Id
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Delete by key value conditions
     *
     * @param array $conditions
     * @return bool
     */
    public function deleteByKeyValueConditions(array $conditions = [])
    {
        return $this->repository->deleteByKeyValueConditions($conditions);
    }
}
