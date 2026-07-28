<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Formative score (Activity / Continuous Assessment - 20% or out of 3.0 / 20)
            $table->float('formative_score')->nullable();
            // Summative score (End of term exam - 80% or out of 100)
            $table->float('summative_score')->nullable();
            // Computed total percentage
            $table->float('total_score')->nullable();
            
            $table->string('grade')->nullable(); // A*, A, B, C, D, E, F, G
            $table->float('level_of_achievement')->nullable(); // NT/3 (Total / 100 * 3)
            $table->integer('identifier')->nullable(); // 1, 2, 3
            $table->string('descriptor')->nullable(); 
            $table->text('teacher_comment')->nullable();
            
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'term_id'], 'student_subject_term_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
