<?php

use App\Support\MoneyManager;
use Money\Money;

test('create money', function (Money $money) {
    expect($money)->toBeMoney();
    expect($money)->toCost('1', 'TRY');
})->with([
    fn () => MoneyManager::createMoney('100'),
]);

test('parse by intl localized decimal', function (Money $money) {
    expect($money)->toBeMoney();
    expect($money)->toCost('1234.58', 'TRY');

    $value = MoneyManager::parseByIntlLocalizedDecimal($money);

    expect($money)->toCost($value);
})->with([
    fn () => MoneyManager::parseByIntlLocalizedDecimal('1.234,58'),
]);

test('parse by decimal', function (Money $money) {
    expect($money)->toBeMoney();
    expect($money)->toCost('1234.58', 'TRY');

    $value = MoneyManager::parseByDecimal($money);

    expect($money)->toCost($value);
})->with([
    fn () => MoneyManager::parseByDecimal('1234.58'),
]);

test('format by intl', function () {
    $money = MoneyManager::parseByDecimal('1234.58');
    $formattedMoney = MoneyManager::formatByIntl($money);

    expect($formattedMoney)->toEqual('₺1.234,58');
});

test('format by decimal', function () {
    $money = MoneyManager::parseByDecimal('1234.58');
    $formattedMoney = MoneyManager::formatByDecimal($money);

    expect($formattedMoney)->toEqual('1234.58');
});

test('get trend', function (Money $money, int $expected) {
    $trend = MoneyManager::getTrend($money);

    expect($trend)->toEqual($expected);
})->with([
    [fn () => MoneyManager::parseByDecimal('1234.58'), 1],
    [fn () => MoneyManager::parseByDecimal('-1234.58'), -1],
    [fn () => MoneyManager::parseByDecimal('0'), 0],
]);
