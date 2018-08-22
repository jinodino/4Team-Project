<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            
            
            $table->string('login_id', 45)->primary();
            $table->foreign('login_id')->references('login_id')->on('users')->onDelete('Restrict')->onUpdate('cascade');
            //$table->string('name_eng', 30)->nullable();
            $table->string('name', 30)->nullable();
            $table->string('name_kana', 30)->nullable();
            $table->string('email', 90);
            $table->text('profile_photo_url');
            $table->date('birth_date');
            $table->timestamp('data_entry_time')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
