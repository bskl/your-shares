<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Contracts\TransactionRepository;
use App\Repositories\EloquentBaseRespository;

class EloquentTransactionRepository extends EloquentBaseRepository implements TransactionRepository
{
    /**
     * The transaction instance.
     */
    protected $model;

    /**
     * Create a new repository instance.
     *
     * @param  App\Models\Transaction  $transaction
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    /**
     * Create a new transaction for the given data.
     *
     * @param  array  $data
     * @return App\Models\Transaction  $transaction
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update the transaction with given data.
     *
     * @param  App\Models\Transaction  $transaction
     * @param  array   $data
     * @return App\Models\Transaction  $transaction
     */
    public function update(array $data, Transaction $transaction)
    {
        
    }

    /**
     * Delete the transaction.
     *
     * @param  App\Models\Transaction  $transaction
     * @return void
     */
    public function delete(Transaction $transaction)
    {
        
    }
}