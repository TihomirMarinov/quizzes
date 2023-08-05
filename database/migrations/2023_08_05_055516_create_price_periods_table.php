<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('price_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', '10', '2');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->unsignedInteger('created_by');
            $table->dateTime('created_at');
            $table->unsignedInteger('updated_by');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_periods');
    }
};
