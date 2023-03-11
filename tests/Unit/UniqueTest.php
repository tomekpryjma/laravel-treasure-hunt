<?php

namespace Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Helpers\Unique;

class UniqueTest extends TestCase
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
            [$column => Unique::uuid($tableName, $column)],
            [$column => Unique::uuid($tableName, $column)],
            [$column => Unique::uuid($tableName, $column)],
            [$column => Unique::uuid($tableName, $column)],
        ];

        DB::table($tableName)->insert($inserts);

        $uuid = Unique::uuid($tableName, $column, $fakerUuid);

        \Illuminate\Support\Str::isUuid($uuid);

        $this->assertEquals(count($inserts), DB::table($tableName)->select('id')->count());
    }
}
