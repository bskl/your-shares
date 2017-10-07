<?php

namespace App\Http\Controllers\API;

use App\Contracts\TransactionRepository;
use App\Http\Requests\API\TransactionRequest;
use App\Events\BuyingTransactionCreated;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * The transactions instance.
     */
    protected $transactions;

    /**
     * Create a new controller instance.
     *
     * @param  App\Contracts\TransactionRepository  $transactions
     * @return void
     */
    public function __construct(TransactionRepository $transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Create a new transaction instance for auth user after a valid request.
     *
     * @param  TransactionRequest     $request
     * @return App\Models\Portfolio $portfolio
     */
    public function store(TransactionRequest $request)
    {
        $data = $request->all();
        $data['type'] = $data['type']['value'];

        $transaction = $this->transactions->create($data);

        $portfolioSymbol = event(new BuyingTransactionCreated($transaction));

        return response()->json($portfolioSymbol[0]);
    }

    /**
     * Update given portfolio instance after a valid request.
     *
     * @param  TransactionRequest     $request
     * @param  App\Models\Portfolio $portfolio
     * @return App\Models\Portfolio $portfolio
     */
    public function update()
    {
        
    }

    /**
     * Delete a portfolio.
     *
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function destroy()
    { 
        
    }
}