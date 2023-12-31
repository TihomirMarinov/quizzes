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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('question_id');
            $table->string('answer', 255);
            $table->unsignedTinyInteger('is_true');
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
        Schema::dropIfExists('quiz_answers');
    }
};
