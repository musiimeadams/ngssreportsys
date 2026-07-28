<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            
            $table->integer('attendance_present')->default(0);
            $table->integer('total_attendance')->default(0);
            
            $table->text('conduct_comment')->nullable();
            $table->text('class_teacher_comment')->nullable();
            $table->text('headteacher_comment')->nullable();
            
            $table->integer('position')->nullable();
            $table->string('decision')->nullable(); // Promoted, Retained, Pending
            $table->string('status')->default('draft'); // draft, published
            
            $table->timestamps();

            $table->unique(['student_id', 'term_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_cards');
    }
};
