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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
