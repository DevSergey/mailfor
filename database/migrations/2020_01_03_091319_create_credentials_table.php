<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateCredentialsTable extends Migration
{
    public function up()
    {
        Schema::create('credentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('host');
            $table->unsignedInteger('port');
            $table->string('from_address');
            $table->string('from_name');
            $table->string('encryption');
            $table->string('username');
            $table->string('password');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('credentials');
    }
}
