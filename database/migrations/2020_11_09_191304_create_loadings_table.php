<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loadings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_number')->nullable();
            $table->string('reference_via')->nullable();
            $table->string('reference_date')->nullable();
            $table->string('reference_batch')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['reference_via', 'reference_date', 'reference_batch'], 'reference_unique');
        });

        Schema::create('loading_items', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->string('notes')->nullable();
            $table->foreignId('loading_id');
            $table->foreignId('item_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('loading_id')
                ->on('loadings')->references('id')
                ->key('loading_items_loading_id_foreign')
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
        Schema::dropIfExists('loading_items');
        Schema::dropIfExists('loadings');
    }
}
