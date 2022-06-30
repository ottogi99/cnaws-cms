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
        Schema::create('farmlands', function (Blueprint $table) {
            $table->id();
            // 소규모/영세농
            $table->decimal('rice_field', 10, 2)->nullable();   // 답작 (논)
            $table->decimal('field', 10, 2)->nullable();        // 전작 (밭)
            $table->decimal('other_field', 10, 2)->nullable();  // 기타
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
        Schema::dropIfExists('farmlands');
    }
};
