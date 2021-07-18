<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_videos', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->date('date');
            $table->integer('views');
            $table->integer('likes');
            $table->integer('comments');
            $table->unsignedBigInteger('video_id');
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            // relation
            $table->foreign('video_id')->references('id')->on('videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_videos');
    }
}
