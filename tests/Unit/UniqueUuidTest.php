<?php

namespace Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Helpers\UniqueUuid;

class UniqueUuidTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_uuid_generation()
    {
        $tableName = 'test_uuid_generation';
        $column = 'uuid_column';
        Schema::create($tableName, function (Blueprint $table) use ($column) {
            $table->temporary();
            $table->id();
            $table->uuid($column);
        });

        $fakerUuid = \Faker\Factory::create()->uuid();
        $inserts = [
            [$column => $fakerUuid],
            [$column => UniqueUuid::generate($tableName, $column)],
            [$column => UniqueUuid::generate($tableName, $column)],
            [$column => UniqueUuid::generate($tableName, $column)],
            [$column => UniqueUuid::generate($tableName, $column)],
        ];

        DB::table($tableName)->insert($inserts);

        $uuid = UniqueUuid::generate($tableName, $column, $fakerUuid);

        \Illuminate\Support\Str::isUuid($uuid);

        $this->assertEquals(count($inserts), DB::table($tableName)->select('id')->count());
    }
}
