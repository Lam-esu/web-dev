<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key to employees table
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade')->onUpdate('cascade');
            
            $table->date('attendance_date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            // Ensures only one attendance row per employee per day
            $table->unique(['employee_id', 'attendance_date'], 'unique_employee_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};