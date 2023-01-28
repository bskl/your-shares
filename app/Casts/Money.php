<?php

namespace App\Casts;

use App\Support\MoneyManager;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Money\Money as PhpMoney;

class Money implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * {@inheritdoc}
     */
    public function get($model, string $key, $value, array $attributes): PhpMoney
    {
        return MoneyManager::createMoney($value ?? '0');
    }

    /**
     * {@inheritdoc}
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        return is_null($value) ? '0' : MoneyManager::parseByDecimal($value)->getAmount();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($model, string $key, $value, array $attributes): string
    {
        return MoneyManager::formatByIntl($value);
    }
}
