<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /* @var model abstract */
    protected $model;

    /**
     * Constructor of class
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all the data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * Get all the data with order by
     *
     * @param string $orderBy
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithOrderBy(string $orderBy, string $order = 'asc')
    {
        return $this->model->orderBy($orderBy, $order)->get();
    }

    /**
     * Get data by Id
     *
     * @param string $id
     * @return mixed(Model|Null)
     */
    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Find data by Id with relations
     *
     * @param int $id
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByIdWithRelations(int $id, array $relations = []) {
        return $this->model->with($relations)->find($id);
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
        return $this->model->where($column, $condition)
            ->first();
    }

    /**
     * Get the data by multiple conditions
     *
     * @param array $args
     * @return mixed(Model|Null)
     */
    public function getByConditions(array $args = [], array $select = ['*'])
    {
        return $this->model->select($select)->where($args)
            ->first();
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
        return $this->model->select($select)->where($args)
            ->orderBy($orderBy, $order)
            ->get();
    }

    /**
     * Get all by conditions
     *
     * @param array $conditions
     * @return mixed(Model|Null)
     */
    public function getAllByConditions(array $conditions = [])
    {
        return $this->model->where($conditions)->get();
    }

    /**
     * Create data by array attributes
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes = [])
    {
        return $this->model->create($attributes);
    }

    /**
     * Insert data by array attributes
     *
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes = [])
    {
        return $this->model->insert($attributes);
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
        $maxValue = $this->model
            ->where($conditions)
            ->max($column);

        $attributes[$column] = $maxValue ? $maxValue + 1 : 1;

        return $this->model->create($attributes);
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
        $result = $this->model->find($id);
        if (empty($result)) {
            return false;
        }

        return $result->update($attributes);
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
        return $this->model->updateOrCreate($condition, $data);
    }

    /**
     * Delete data by Id
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $result = $this->model->find($id);
        if (empty($result)) {
            return false;
        }

        return $result->delete();
    }

    /**
     * Delete by key value conditions
     *
     * @param array $conditions
     * @return bool
     */
    public function deleteByKeyValueConditions(array $conditions = [])
    {
        if (empty($conditions)) {
            return false;
        }

        return $this->model->where($conditions)
            ->delete();
    }

    /**
     * First or create
     *
     * @param array $condition
     *        If $condition contains two sub-arrays:
     *        - The first sub-array specifies the search conditions.
     *        - The second sub-array provides the values for creation if no match is found.
     *        Example: [['email' => 'example@email.com'], ['name' => 'John Doe']]
     *        If only one array is provided, it will be used for both searching and creation.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $condition)
    {
        return $this->model->firstOrCreate(...$condition);
    }

    /**
     * Get data by column in
     *
     * @param string $column
     * @param array $values
     * @return mixed(\Illuminate\Database\Eloquent\Collection | null)
     */
    public function getByColumnIn(string $column, array $values)
    {
        return $this->model
            ->whereIn($column, $values)
            ->get();
    }
}
