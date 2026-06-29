<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $dataType = DB::table('data_types')->where('name', 'orders')->first();

        if ($dataType) {
            DB::table('data_rows')
                ->where('data_type_id', $dataType->id)
                ->where('field', 'items')
                ->update(['browse' => 0, 'read' => 0, 'edit' => 0, 'add' => 0]);
        }
    }

    public function down()
    {
        $dataType = DB::table('data_types')->where('name', 'orders')->first();

        if ($dataType) {
            DB::table('data_rows')
                ->where('data_type_id', $dataType->id)
                ->where('field', 'items')
                ->update(['browse' => 1, 'read' => 1]);
        }
    }
};
