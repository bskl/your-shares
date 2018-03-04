<?php

namespace App\Enums;

use ReflectionClass;

abstract class Enum
{
    public static function getTypes()
    {
        $class = new ReflectionClass(get_called_class());

        return $class->getConstants();
    }

    public static function getTypeId($type)
    {
        $class = new ReflectionClass(get_called_class());

        return $class->getConstant(strtoupper($type));
    }

    public static function getTypeName($id)
    {
        $types = array_flip(self::getTypes());

        return title_case($types[$id]);
    }
}
