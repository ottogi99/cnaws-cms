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
        Schema::create('farmhouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birthday');
            $table->enum('gender', ['M', 'F']);
            $table->string('address');
            $table->string('contact', 11);
            $table->enum('size', ['S', 'L']);

            // 소규모/영세농
            $table->decimal('rice_field', 10, 2)->nullable();   // 답작 (논)
            $table->decimal('field', 10, 2)->nullable();        // 전작 (밭)
            $table->decimal('other_field', 10, 2)->nullable();  // 기타
            // 대규모/전업농
            $table->decimal('area', 10, 2)->nullable();         // 소유 경지면적
            $table->string('items')->nullable();                // 재배품목
            // 외래키
            $table->foreignId('nonghyup_id')->constrained()->before('name');
            $table->foreignId('account_id')->nullable()->constrained()->after('contact');

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
        Schema::dropIfExists('farmers');
    }
};
