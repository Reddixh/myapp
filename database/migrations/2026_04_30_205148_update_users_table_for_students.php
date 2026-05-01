<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('reg_number')->unique()->nullable()->after('name');
            $table->string('programme')->nullable()->after('reg_number');
            $table->string('year')->nullable()->after('programme');
            $table->string('college')->nullable()->after('year');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reg_number', 'programme', 'year', 'college']);
        });
    }
};