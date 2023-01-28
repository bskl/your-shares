<?php

use App\Enums\TransactionType;

return [
    TransactionType::class => [
        TransactionType::Buying->name => 'Alım',
        TransactionType::Sale->name => 'Satım',
        TransactionType::Dividend->name => 'Temettü',
        TransactionType::Bonus->name => 'Bedelsiz',
        TransactionType::Rights->name => 'Bedelli',
        TransactionType::MergerOut->name => 'Birleşme Çıkış',
        TransactionType::MergerIn->name => 'Birleşme Giriş',
        TransactionType::PublicOffering->name => 'Halka Arz',
    ],
];
