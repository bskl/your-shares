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
     * @param  int  $id
     * @return App\Models\  $model
     */
    public function findOrFail($id)
	{
		return $this->model->findOrFail($id);
	}
}