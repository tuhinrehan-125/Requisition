<?php

use App\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        $insert_data = [
            [
                'role' => 'super-admin',
            ],
            [
                'role' => 'dept-user',
            ],
            [
                'role' => 'admin-officer',
            ],
            [
                'role' => 'sr-officer',
            ],
        ];
        foreach ($insert_data as $data) {
            Role::create($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
