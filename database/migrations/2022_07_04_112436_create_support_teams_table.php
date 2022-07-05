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
        Schema::create('support_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birthday');
            $table->enum('gender', ['M', 'F']);
            $table->string('address');
            $table->string('contact', 11);
            $table->enum('size', ['S', 'L']);

            $table->boolean('insurance')->nullable();

            // $table->foreign('management_year')->references('year')->on('management')->onDelete('cascade');
            $table->foreignId('nonghyup_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('cascade');
            // $table->foreignId('machinery_id')->nullable()->constrained()->onDelete('cascade');
            // $table->foreignId('training_id')->nullable()->constrained()->onDelete('cascade');

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('support_teams');
        Schema::enableForeignKeyConstraints();
    }
};
