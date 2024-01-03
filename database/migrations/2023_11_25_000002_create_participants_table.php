<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id');
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
            $table->boolean('party_owner')->default(false);
            $table->string('name');
            $table->string('email');
            $table->string('secret_santa')->nullable();
            $table->string('token', 60)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
