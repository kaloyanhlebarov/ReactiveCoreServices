<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('password');
            $table->string('name');
            $table->bigInteger('country_id')->unsigned();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
