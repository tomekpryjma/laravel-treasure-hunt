<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class Unique
{
    /**
     * Generates a unique UUID in a table column in order to prevent validation failure.
     * The param $testUuid can only be used in dev mode and should only be used in
     * test cases.
     */
    public static function uuid(string $table, string $column, string $testUuid = null)
    {
        $uuid = Str::uuid();

        if (App::environment('local') && $testUuid !== null) {
            $uuid = $testUuid;
        }

        if (DB::table($table)->select($column)->where($column, $uuid)->count()) {
            self::uuid($table, $column);
        }

        return $uuid;
    }

    public static function number(string $table, string $column)
    {
        $number = mt_rand(10000, 99999);

        if (DB::table($table)->select($column)->where($column, $number)->count()) {
            self::number($table, $column);
        }

        return $number;
    }
}
