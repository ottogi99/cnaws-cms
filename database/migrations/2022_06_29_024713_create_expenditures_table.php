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
        Schema::create('expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('item')->nullable();
            $table->string('target')->nullable();
            $table->string('details')->nullable();
            $table->decimal('amount', 6, 2)->nullable();
            $table->date('payment_at')->nullable();
            $table->timestamps();

            // $table->foreignId('account_id')->nullable()->constrained()->after('spent_at');
            $table->foreignId('nonghyup_id')->nullable()->constrained()->after('type');
            $table->foreignId('staff_id')->nullable()->constrained()->after('target');
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
        Schema::dropIfExists('expenditures');
        Schema::enableForeignKeyConstraints();
    }
};
