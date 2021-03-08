<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockOfficerUserReceivedToOrderApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_approvals', function (Blueprint $table) {
            $table->integer('stock_officer')->nullable()->after('order_id');
            $table->integer('user_received')->nullable()->after('stock_officer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_approvals', function (Blueprint $table) {
            //
        });
    }
}
