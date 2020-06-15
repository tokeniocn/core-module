<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->string('nick_name')->default('')->comment('昵称');
            $table->string('real_name')->default('')->comment('真实姓名');
            $table->unsignedTinyInteger('age')->default(0)->comment('年龄');
            $table->unsignedTinyInteger('sex')->default(3)->comment('性别，1=男，2=女，3=未知');
            $table->string('country')->default('')->comment('国家/地区');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_infos');
    }
}
