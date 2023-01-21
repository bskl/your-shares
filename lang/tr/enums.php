<?php

use App\Enums\TransactionType;

return [
    TransactionType::class => [
        TransactionType::Buying => 'Alım',
        TransactionType::Sale => 'Satım',
        TransactionType::Dividend => 'Temettü',
        TransactionType::Bonus => 'Bedelsiz',
        TransactionType::Rights => 'Bedelli',
        TransactionType::MergerOut => 'Birleşme Çıkış',
        TransactionType::MergerIn => 'Birleşme Giriş',
        TransactionType::PublicOffering => 'Halka Arz',
    ],
];
