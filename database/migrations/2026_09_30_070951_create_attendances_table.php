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
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        
        // Foreign Key to employees table (Replaces student_id)
        $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade')->onUpdate('cascade');
        
        $table->date('attendance_date');
        $table->time('attendance_time');
        
        $table->timestamp('recorded_at')->useCurrent();
        $table->timestamps();

        // Unique Key constraint from the SQL template
        $table->unique(['employee_id', 'attendance_date'], 'unique_employee_date');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
