<?php

namespace App\Traits;

trait RendersSelect
{
    public static function renderSelect()
    {
        $value_column = self::SELECT_COLUMN;
        $id_column = self::SELECT_ID;
        return self::select("{$id_column} as id", "{$value_column} as value")->get();
    }
}