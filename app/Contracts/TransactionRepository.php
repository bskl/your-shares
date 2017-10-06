<?php

namespace App\Contracts;

use App\Models\Transaction;
use App\Contracts\BaseContract;

interface TransactionRepository extends BaseRepository
{
    /**
     * Create a new transaction symbol for the given data.
     *
     * @param  array  $data
     * @return App\Models\Transaction  $Transaction
     */
    public function create(array $data);

    /**
     * Update the transaction with given data.
     *
     * @param  App\Models\Transaction  $transaction
     * @param  array   $data
     * @return App\Models\Transaction  $transaction
     */
    public function update(array $data, Transaction $transaction);

    /**
     * Delete the transaction symbol.
     *
     * @param  App\Models\Transaction  $transaction
     * @return void
     */
    public function delete(Transaction $transaction);
}