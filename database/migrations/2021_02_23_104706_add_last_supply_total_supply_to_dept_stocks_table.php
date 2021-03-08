<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastSupplyTotalSupplyToDeptStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dept_stocks', function (Blueprint $table) {
            $table->integer('last_supply')->nullable()->after('product_id');
            $table->integer('total_supply')->nullable()->after('last_supply');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dept_stocks', function (Blueprint $table) {
            //
        });
    }
}
