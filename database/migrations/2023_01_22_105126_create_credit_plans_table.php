<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name')->nullable();
            $table->double('plan_amount')->nullable();
            $table->integer('plan_credit')->nullable();
            $table->integer('plan_status')->nullable();
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
        Schema::dropIfExists('credit_plans');
    }
};
