<?php

namespace App\Contracts;

use App\Models\Symbol;
use App\Models\Portfolio;

interface SymbolRepository extends BaseRepository
{
    /**
     * Update the portfolio with given data.
     *
     * @param  array   $data
     * @return App\Models\Symbol  $symbols
     */
    public function searchByCode(string $data);
}