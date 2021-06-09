<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbox', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('journal_id');
            $table->string('inbox_number');
            $table->string('sender');
            $table->string('regarding');
            $table->date('entry_date');
            $table->string('inbox_origin');
            $table->string('notes');
            $table->boolean('status');
            $table->string('file');
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
        Schema::dropIfExists('inbox');
    }
}
