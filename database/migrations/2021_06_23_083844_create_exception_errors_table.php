<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExceptionErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exception_errors', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->text('message')->nullable();
            $table->string('code');
            $table->text('file');
            $table->bigInteger('line');
            $table->text('trace');
            $table->text('trace_as_string');
            $table->tinyInteger('is_solve')->default(0)->comment('Is it resolved 0 not resolved 1 resolved');
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
        Schema::dropIfExists('exception_errors');
    }
}
