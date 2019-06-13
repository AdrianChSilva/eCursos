<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            /**
             * No es una relación muchos a muchos porque un requisito puede estar muy relacionado
             * con el curso que se va a impartir, por lo tanto un requisito no va a afectar a muchos cursos, eso sí,
             * un curso puede tener muchos requisitos.
             */
            $table->foreign('course_id')->references('id')->on('courses');
            $table->string('requirement');
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
        Schema::dropIfExists('requirements');
    }
}
