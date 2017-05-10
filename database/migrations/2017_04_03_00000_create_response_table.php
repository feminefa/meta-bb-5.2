<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable()->default(null);
            $table->text('leaders');
            $table->text('responder')->nullable()->default(null);
            $table->text('comment')->nullable()->default(null);
            $table->tinyInteger('action')->nullable()->default(null);
            $table->timestamp('action_date')->nullable()->default(null);;
            $table->timestamp('responded_at')->nullable()->default(null);;
            $table->enum('status', ['pending','processed'])->default('pending');;
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
        Schema::dropIfExists('organisations');
    }
}
