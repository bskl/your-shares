<?php

namespace App\Repositories;

use App\Contracts\BaseRepository;

abstract class EloquentBaseRepository implements BaseRepository
{
    /**
     * The model instance.
     */
    protected $model;

    /**
	 * @return mixed
	 */
	public function all()
	{
		return $this->model->all();
    }

    /**
     * @param array $with
     * @return mixed
	 */
	public function withAll(array $with)
	{
		return $this->model->with($with)->get();
    }
    
    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return App\Models\
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * @param  int  $id
     * @return App\Models\  $model
     */
    public function findOrFail(int $id)
	{
		return $this->model->findOrFail($id);
	}
}