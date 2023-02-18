<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class UniqueUuid
{
    /**
     * Generates a unique UUID in a table column in order to prevent validation failure.
     * The param $testUuid can only be used in dev mode and should only be used in
     * test cases.
     */
    public static function generate(string $table, string $column, string $testUuid = null)
    {
        $uuid = Str::uuid();

        if (App::environment('local') && $testUuid !== null) {
            $uuid = $testUuid;
        }

        if (DB::table($table)->select($column)->where($column, $uuid)->count()) {
            self::generate($table, $column);
        }

        return $uuid;
    }
}
