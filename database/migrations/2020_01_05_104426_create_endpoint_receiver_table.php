<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateEndpointReceiverTable extends Migration
{
    public function up()
    {
        Schema::create('endpoint_receiver', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('endpoint_id')->unsigned();
            $table->integer('receiver_id')->unsigned();
            $table->foreign('endpoint_id')->references('id')->on('endpoints');
            $table->foreign('receiver_id')->references('id')->on('receivers');
        });
    }
    public function down()
    {
        Schema::dropIfExists('endpoint_receiver');
    }
}
