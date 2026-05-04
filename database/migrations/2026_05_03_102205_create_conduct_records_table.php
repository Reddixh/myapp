<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conduct_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['misconduct', 'academic', 'financial', 'other']);
            $table->enum('severity', ['low', 'medium', 'high']);
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->date('incident_date');
            $table->date('resolved_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conduct_records');
    }
};