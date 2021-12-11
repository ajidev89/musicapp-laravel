<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->string("url");
            $table->string("image_url");
            $table->string("artist");
            $table->json("featuredArtists")->nullable();
            $table->string("description");
            $table->string("songTitle");
            $table->string("year");
            $table->boolean("isSingle")->nullable();
            $table->bigInteger("albumId")->nullable();
            $table->string("views")->nullable();
            $table->boolean("editorpicks")->nullable();
            $table->boolean("status");
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
        Schema::dropIfExists('music');
    }
}