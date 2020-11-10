<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receives', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_number')->nullable();
            $table->string('reference_batch')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['reference_number', 'reference_batch'], 'reference_unique');
        });

        Schema::create('receive_items', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->string('notes')->nullable();
            $table->foreignId('receive_id');
            $table->foreignId('item_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('receive_id')
                ->on('receives')->references('id')
                ->key('receive_items_receive_id_foreign')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receive_items');
        Schema::dropIfExists('receives');
    }
}
