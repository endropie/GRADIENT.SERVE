<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('item_serials', function (Blueprint $table) {
            $table->id();
            $table->string('serial')->unique();
            $table->foreignId('item_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('item_id')
                ->on('items')->references('id')
                ->key('item_serials_item_id_foreign')
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
        Schema::dropIfExists('item_serials');
        Schema::dropIfExists('items');
    }
}
