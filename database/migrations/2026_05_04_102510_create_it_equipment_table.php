<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('it_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('equipment_name');
            $table->string('serial_number')->nullable();
            $table->string('category');
            $table->date('issued_date');
            $table->date('due_date')->nullable();
            $table->date('return_date')->nullable();
            $table->enum('status', ['issued', 'returned', 'lost'])->default('issued');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_equipment');
    }
};