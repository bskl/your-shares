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

    /**
     * Update the portfolio with given data.
     *
     * @param  array   $data
     * @return App\Models\Symbol  $symbols
     */
    public function searchByCode(string $data)
    {
        return $this->model->select('id', 'code', 'last_price')
                           ->where('code', 'LIKE', "%$data%")
                           ->get();
    }
}