<?php

namespace App\Http\Controllers\API;

use App\Models\Symbol;
use App\Contracts\PortfolioRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * The portfolios instance.
     */
    protected $portfolios;
     
    /**
     * Create a new controller instance.
     *
     * @param  App\Contracts\PortfolioRepository  $portfolios
     * @return void
     */
    public function __construct(PortfolioRepository $portfolios)
    {
        $this->portfolios = $portfolios;
    }
    /**
     * Get a set of application data.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function getData()
    {
        return [
            'user' => Auth()->user(),
            'portfolios' => $this->portfolios->getWithSymbols(),
            'symbols' => Symbol::get(),
        ];
    }
}