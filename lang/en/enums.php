<?php

use App\Enums\TransactionType;

return [
    TransactionType::class => [
        TransactionType::Buying->name => 'Buying',
        TransactionType::Sale->name => 'Sale',
        TransactionType::Dividend->name => 'Dividend',
        TransactionType::Bonus->name => 'Bonus',
        TransactionType::Rights->name => 'Rights',
        TransactionType::MergerOut->name => 'Merger Out',
        TransactionType::MergerIn->name => 'Merger In',
        TransactionType::PublicOffering->name => 'Public Offering',
    ],
];
