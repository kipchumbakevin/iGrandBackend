<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsageStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usage_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('deviceId');
            $table->integer('contentType');//1-editorial, 2-podcast 3-magazine 4-video
            $table->integer('contentId');
            $table->integer('count')->default(0);
            $table->string('date')->default(0);
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
        Schema::dropIfExists('usage_statistics');
    }
}
