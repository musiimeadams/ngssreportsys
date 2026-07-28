<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->unique();
            $table->string('lin')->nullable(); // Learner Identification Number
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->default('M'); // M or F
            $table->date('date_of_birth')->nullable();
            $table->string('house')->nullable(); // e.g. Nile, Kabalega
            $table->string('religion')->nullable(); // e.g. Christian, Muslim
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('active'); // active, graduated, transferred
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
