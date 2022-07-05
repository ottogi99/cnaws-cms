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
        Schema::create('management_support_team', function (Blueprint $table) {
            $table->id();
            $table->year('management_year');
            $table->foreign('management_year')->references('year')->on('management')->onDelete('cascade');
            $table->foreignId('support_team_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_support_team');
    }
};
