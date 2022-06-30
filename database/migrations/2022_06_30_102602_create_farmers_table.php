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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birthday');
            $table->enum('gender', ['M', 'F']);
            $table->string('address');
            $table->string('contact', 11);
            $table->timestamps();

            $table->foreignId('nonghyup_id')->constrained()->before('name');
            $table->foreignId('farmland_id')->nullable()->constrained()->after('contact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmers');
    }
};
