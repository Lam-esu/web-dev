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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('first_name', 80);
        $table->string('last_name', 80);
        $table->string('employee_number', 50)->unique(); // Replaces student_number
        $table->string('department_position', 150); // Replaces year_section_course
        $table->string('picture', 255)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
