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
        Schema::create('powerinais', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('call_id')->nullable();
            $table->string('status')->nullable();
            $table->longText('message')->nullable();
            $table->longText('err_message')->nullable();
            $table->longText('response')->nullable();
            $table->string('is_call_completed')->nullable();
            $table->string('is_send_gohihglevel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('powerinais');
    }
};