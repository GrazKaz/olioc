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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('numer');
            $table->integer('dofinansowanie')->nullable();
            $table->foreignId('section_id')->nullable();
            $table->string('ngo_hns')->nullable();
            $table->string('dodatkowe_info')->nullable();
            $table->string('opis', 1000)->nullable();
            $table->integer('task_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
