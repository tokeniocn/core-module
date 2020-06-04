<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->comment('关联用户ID');
            $table->bigInteger('used_user_id')->nullable()->default(0)->comment('使用的用户ID');
            $table->string('token', 40)->nullable()->default('')->comment('邀请码');
            $table->dateTimeTz('used_at')->nullable()->comment('使用时间');
            $table->dateTimeTz('expired_at')->nullable()->comment('过期时间');
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();

            $table->index(['token'], 'token');
            $table->index(['user_id'], 'user');
        });

        Schema::create('user_invitation_tree', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique()->comment('关联用户ID');
            $table->json('data')->nullable()->comment('用户上级邀请关系, 按数组顺序记录上级邀请关系');
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_invitations');
        Schema::dropIfExists('user_invitation_tree');
    }
}
