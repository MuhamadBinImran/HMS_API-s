<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void
{
Schema::create('prescriptions', function (Blueprint $table) {
$table->id();
$table->foreignId('appointment_id')->constrained()->onDelete('cascade');
$table->text('medicines');     // Example: "Panadol 500mg, 2 times a day"
$table->text('instructions')->nullable(); // Doctor notes or special instructions
$table->timestamps();
});
}

public function down(): void
{
Schema::dropIfExists('prescriptions');
}
};
