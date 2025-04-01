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


        Schema::create('eg1_whatsapp_history', function (Blueprint $table) {
            $table->id();
            $table->string('whatsapp_id');
            $table->longText('messages');
            $table->timestamps();

            $table->foreign('whatsapp_id')->references('whatsapp_id')->on('eg1_whatsapp_integration');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eg1_whatsapp_history');
    }
};
