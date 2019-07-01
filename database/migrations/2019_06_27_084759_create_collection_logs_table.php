<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('collection_id');
            $table->bigInteger('resource_id');
            $table->bigInteger('user_id');
            $table->bigInteger('amount')->unsigned();
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
        Schema::dropIfExists('collection_logs');
    }
}
