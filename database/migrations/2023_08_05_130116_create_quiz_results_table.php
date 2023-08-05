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
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('last_name', 255);
            $table->string('email', 255);
            $table->unsignedTinyInteger('score');
            $table->unsignedTinyInteger('wrong_answers');
            $table->dateTime('expired_at');
            $table->unsignedInteger('created_by')->nullable()->default(0);
            $table->dateTime('created_at');
            $table->unsignedInteger('updated_by')->nullable()->default(0);
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
