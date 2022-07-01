<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_farmhouse', function (Blueprint $table) {
            $table->id();

            $table->year('management_year');
            $table->foreign('management_year')->references('year')->on('management')->onDelete('cascade');
            $table->foreignId('farmhouse_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_farmhouse');
    }
};
