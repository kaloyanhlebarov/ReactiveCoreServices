<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('iso_2');
            $table->string('iso_3')->nullable();
            $table->string('name')->unique();
            $table->string('capital')->nullable();
            $table->float('area', 9, 2)->nullable();
            $table->string('flag')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('currency_symbol')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
