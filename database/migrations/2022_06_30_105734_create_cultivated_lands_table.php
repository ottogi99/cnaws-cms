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
        Schema::create('cultivated_lands', function (Blueprint $table) {
            $table->id();
            // 대규모/전업농
            $table->decimal('area', 10, 2)->nullable();         // 소유 경지면적
            $table->string('items')->nullable();                // 재배품목
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
        Schema::dropIfExists('cultivated_lands');
    }
};
