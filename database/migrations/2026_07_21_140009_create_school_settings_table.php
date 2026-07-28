<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('NGSS SECONDARY SCHOOL');
            $table->string('school_motto')->default('STRIVE FOR EXCELLENCE');
            $table->string('address')->default('P.O. Box 100, Mbarara');
            $table->string('phone')->default('+256 700 000 000');
            $table->string('email')->default('info@ngss.edu');
            $table->date('next_term_begins')->nullable();
            $table->date('next_term_ends')->nullable();
            $table->string('next_term_fees')->default('UGX 850,000');
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
