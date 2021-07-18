<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('name');
            $table->string('video_id');
            $table->string('thumbnail');
            $table->longText('description');
            $table->integer('views');
            $table->integer('likes');
            $table->integer('comments');
            $table->unsignedBigInteger('channel_id');
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            // relation
            $table->foreign('channel_id')->references('id')->on('channels');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
