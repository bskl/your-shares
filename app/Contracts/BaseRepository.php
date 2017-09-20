<?php

namespace App\Contracts;

interface BaseRepository
{
    /**
	 * @return mixed
	 */
	public function all();
    
    /**
     * @param  int  $id
     * @return App\Models\  $model
     */
    public function findOrFail($id);
}