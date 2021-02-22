<?php

use App\Enums\TransactionType;

return [
    TransactionType::class => [
        TransactionType::Buying   => 'Buying',
        TransactionType::Sale     => 'Sale',
        TransactionType::Dividend => 'Dividend',
        TransactionType::Bonus    => 'Bonus',
        TransactionType::Rights   => 'Rights',
        TransactionType::Merger   => 'Merger',
    ],
];
