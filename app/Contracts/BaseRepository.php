<?php

namespace App\Contracts;

interface BaseRepository
{
    /**
	 * @return mixed
	 */
    public function all();
    
    /**
     * @param array $with
     * @return mixed
	 */
    public function withAll(array $with);
    
    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return App\Models\
     */
    public function updateOrCreate(array $attributes, array $values = []);
    
    /**
     * @param  int  $id
     * @return App\Models\  $model
     */
    public function findOrFail(int $id);
}