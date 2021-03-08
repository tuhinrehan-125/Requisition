<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('admin_officer')->nullable();
            $table->integer('sr_officer')->nullable();
            $table->integer('rejection_by_admin')->nullable();
            $table->integer('rejection_by_officer')->nullable();
            $table->integer('forward_to_officer')->nullable();
            $table->text('rejection_note_by_admin')->nullable();
            $table->text('rejection_note_by_officer')->nullable();
            $table->timestamps();

            $table->index('admin_officer');
            $table->index('sr_officer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_approvals');
    }
}
