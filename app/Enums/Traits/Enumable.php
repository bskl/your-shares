<?php

namespace App\Enums\Traits;

use BackedEnum;
use Error;
use Illuminate\Support\Facades\Lang;
use UnitEnum;

trait Enumable
{
    /**
     * Get all or a custom set of the enum names.
     *
     * @param  \UnitEnum[]  $values
     * @return array
     */
    public static function getNames(UnitEnum|array $values = null): array
    {
        if (is_null($values)) {
            $values = self::cases();
        }

        return array_column(is_array($values) ? $values : func_get_args(), 'name');
    }

    /**
     * Get the enum name.
     *
     * @param  \UnitEnum|string  $name
     * @return string
     */
    public static function getName(UnitEnum|string $name): string
    {
        $enum = is_string($name)
            ? static::fromName($name)
            : $name;

        return $enum->name;
    }

    /**
     * Get all or a custom set of the enum values.
     *
     * @param  \BackedEnum[]  $values
     * @return array
     */
    public static function getValues(BackedEnum|array $values = null): array
    {
        if (is_null($values)) {
            $values = self::cases();
        }

        return array_column(is_array($values) ? $values : func_get_args(), 'value');
    }

    /**
     * Get the enum value.
     *
     * @param  \BackedEnum|string  $name
     * @return int|string
     */
    public static function getValue(BackedEnum|string $name): int|string
    {
        $enum = is_string($name)
            ? static::fromName($name)
            : $name;

        return $enum->value;
    }

    /**
     * Get enum class from name if case defined else throw error.
     *
     * @param  string  $name
     * @return static
     *
     * @throw  \Error
     */
    public static function fromName(string $name): static
    {
        if (is_null($enum = static::tryFromName($name))) {
            throw new Error(sprintf(
                'Undefined constant  %s::%s',
                static::class,
                $name
            ));
        }

        return $enum;
    }

    /**
     * Get enum class from name if case defined else return null.
     *
     * @param  string  $name
     * @return static|null
     */
    public static function tryFromName(string $name): ?static
    {
        $names = self::getNames();

        if (false !== $key = array_search($name, $names)) {
            return self::cases()[$key];
        }

        return null;
    }

    /**
     * Get the enum as an array formatted for a select.
     * [mixed $value => string label()].
     *
     * @param  \BackedEnum  $enums
     * @return array
     */
    public static function asSelectArray(BackedEnum $enums = null): array
    {
        $selectArray = [];

        foreach ($enums ? func_get_args() : self::cases() as $enumCase) {
            $selectArray[$enumCase->value] = $enumCase->label();
        }

        return $selectArray;
    }

    /**
     * Get the localization of the enum.
     *
     * @return string|null
     */
    public function label(): ?string
    {
        $localizedKey = 'enums.'.self::class.'.'.$this->name;

        if (Lang::has($localizedKey)) {
            return trans($localizedKey);
        }

        return null;
    }

    /**
     * Check that the enum contains a specific value.
     *
     * @param  int|string  $value
     * @return bool
     */
    public static function hasValue(int|string $value): bool
    {
        $validValues = static::getValues();

        return in_array($value, $validValues, true);
    }

    /**
     * Checks if this instance is equal to the given enum instance or value.
     *
     * @param  \UnitEnum|string|int  $enumValue
     * @return bool
     */
    public function is(UnitEnum|string|int $enumValue): bool
    {
        if ($enumValue instanceof UnitEnum) {
            return $this === $enumValue;
        }

        return $this === self::tryFrom($enumValue);
    }

    /**
     * Checks if this instance is not equal to the given enum instance or value.
     *
     * @param  \UnitEnum|int|string  $enumValue
     * @return bool
     */
    public function isNot(UnitEnum|string|int $enumValue): bool
    {
        return ! $this->is($enumValue);
    }

    /**
     * Checks if a matching enum instance or value is in the given array.
     *
     * @param  iterable  $values
     * @return bool
     */
    public function in(iterable $values): bool
    {
        foreach ($values as $value) {
            if ($this->is($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if a matching enum instance or value is not in the given array.
     *
     * @param  iterable  $values
     * @return bool
     */
    public function notIn(iterable $values): bool
    {
        foreach ($values as $value) {
            if ($this->is($value)) {
                return false;
            }
        }

        return true;
    }
}
