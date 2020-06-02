<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Models\Frontend\UserCertify;

class CreateUserCertifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_certifies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name')->default('')->comment('名字');
            $table->string('obverse')->default('')->comment('正面照');
            $table->string('reverse')->default('')->comment('反面照');
            $table->unsignedTinyInteger('certify_type')->default(UserCertify::CERTIFY_TYPE_IDENTIFICATION)->comment('实名认证证件类型');
            $table->string('number')->default('')->comment('证件号码');
            $table->tinyInteger('status')->default(UserCertify::STATUS_WAITING)->comment('0=未审核 1=审核通过 -1=驳回');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_certifies');
    }
}
