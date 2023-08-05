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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question', 255);
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('quiz_questions');
    }
};
