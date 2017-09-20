<?php

namespace App\Repositories;

use App\Models\Symbol;
use App\Contracts\SymbolRepository;
use App\Repositories\EloquentBaseRespository;

class EloquentSymbolRepository extends EloquentBaseRepository implements SymbolRepository
{
    /**
     * The symbol instance.
     */
    protected $model;

    /**
     * Create a new repository instance.
     *
     * @param  App\Models\Symbol  $symbol
     * @return void
     */
    public function __construct(Symbol $symbol)
    {
        $this->model = $symbol;
    }
}