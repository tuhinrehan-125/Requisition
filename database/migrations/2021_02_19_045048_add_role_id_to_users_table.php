<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->nullable()->after('status');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $insert_data = [
                [
                    'name' => 'Alex',
                    'email' => 'superadmin@gmail.com',
                    'email_verified_at' => now(),
                    'password' => bcrypt('admin12'),
                    'role_id'=>1
                ],
            ];
            foreach ($insert_data as $data) {
                User::create($data);
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
