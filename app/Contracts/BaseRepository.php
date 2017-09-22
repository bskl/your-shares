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
     * @param  int  $id
     * @return App\Models\  $model
     */
    public function findOrFail($id);
}