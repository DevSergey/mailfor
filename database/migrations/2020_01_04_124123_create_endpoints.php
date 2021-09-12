<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateEndpoints extends Migration
{
    public function up()
    {
        Schema::create('endpoints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('cors_origin');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
    public
    function down()
    {
        Schema::dropIfExists('endpoints');
    }
}
