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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->decimal('lab_tests_fee', 10, 2)->default(0);
            $table->decimal('medicine_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('unpaid'); // unpaid, paid, cancelled
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
