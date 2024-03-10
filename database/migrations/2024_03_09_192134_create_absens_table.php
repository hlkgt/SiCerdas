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
        Schema::create('user_absens', function (Blueprint $table) {
            $table->id();
            $table->text('username');
            $table->text('class');
            $table->enum('present', ['hadir', 'sakit', 'izin', 'tanpa-keterangan']);
            $table->text('information');
            $table->enum('status', ['approve', 'rejected'])->nullable();
            $table->dateTime('status_confirmation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_absens');
    }
};
