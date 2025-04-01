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
        Schema::create('eg1_whatsapp_integration', function (Blueprint $table) {
            $table->string('whatsapp_id')->primary()->index()->unique();
            $table->string('typebot_session_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eg1_whatsapp_integration');
    }
};
