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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('nonghyup_id');
            $table->year('year');
            $table->unsignedDecimal('total', 10, 2);
            $table->unsignedDecimal('do', 8, 2)->nullable();
            $table->unsignedDecimal('sigun', 8, 2)->nullable();
            $table->unsignedDecimal('center', 8, 2)->nullable();
            $table->unsignedDecimal('unit', 8, 2)->nullable();
            $table->timestamps();

            // 외래키 정의
            $table->foreignId('nonghyup_id')->constrained()->before('year')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('year')->references('year')->on('management');

            $table->unique(['nonghyup_id', 'year']);
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
        Schema::dropIfExists('expenses');
        Schema::enableForeignKeyConstraints();
    }
};
