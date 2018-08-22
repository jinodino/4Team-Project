<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessorBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professor_books', function (Blueprint $table) {
            
            $table->increments('no');
            $table->string('login_id', 45);
            $table->foreign('login_id')->references('login_id')->on('professors')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('faculty_id')->unsigned();
            $table->foreign('faculty_id')->references('faculty_id')->on('faculties')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('email', 90)->nullable();
            $table->string('name', 30)->nullable();
            $table->date('birth_date');
            $table->string('employ_year', 10);
            $table->string('certification_key', 255)->nullable();  //이메일 인증키 추가
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
        Schema::dropIfExists('professor_books');
    }
}
