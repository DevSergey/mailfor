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
            $table->string('subject');
            $table->unsignedInteger('monthly_limit');
            $table->unsignedInteger('client_limit');
            $table->string('time_unit');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('credential_id');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('credential_id')
                ->references('id')
                ->on('credentials')
                ->onDelete('restrict');
        });
    }
    public
    function down()
    {
        Schema::dropIfExists('endpoints');
    }
}
