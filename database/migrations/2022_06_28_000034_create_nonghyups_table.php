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
        Schema::create('nonghyups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 127);
            $table->string('address', 127);
            $table->string('contact', 11);
            $table->string('representative', 127);
            $table->tinyInteger('sequence')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // 외래키 정의
            $table->foreignId('city_id')->constrained()->before('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('nonghyups');
        Schema::enableForeignKeyConstraints();
    }
};
