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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username', 50)->unique(); // From the SQL template
        $table->string('full_name', 120); // From the SQL template
        $table->string('email')->unique()->nullable(); 
        $table->string('password'); // Laravel handles password_hash automatically
        $table->rememberToken();
        $table->timestamps(); // Handles created_at and updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
