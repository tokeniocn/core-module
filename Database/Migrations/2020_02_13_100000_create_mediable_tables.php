<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediableTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('disk', 32);
            $table->string('directory');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('extension', 32);
            $table->string('mime_type', 128);
            $table->string('aggregate_type', 32);
            $table->integer('size')->unsigned();
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();

            $table->index(['disk', 'directory'], 'disk_directory');
            $table->unique(['disk', 'directory', 'filename', 'extension'], 'disk_file');
            $table->index('aggregate_type', 'aggregate_type');
        });

        Schema::create('mediables', function (Blueprint $table) {
            $table->bigInteger('media_id')->unsigned();
            $table->string('mediable_type');
            $table->bigInteger('mediable_id')->unsigned();
            $table->string('tag');
            $table->integer('order')->unsigned();

            $table->primary(['media_id', 'mediable_type', 'mediable_id', 'tag'], 'mediable');
            $table->index(['mediable_id', 'mediable_type'], 'media_type');
            $table->index('tag', 'tag');
            $table->index('order', 'order');
            $table->foreign('media_id', 'media_foreign')
                ->references('id')->on('media')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mediables');
        Schema::drop('media');
    }
}
