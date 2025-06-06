<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscriber_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subscriber_id');
            $table->foreignId('email_sequence_id')->constrained('email_sequences')->onDelete('cascade');
            $table->integer('current_step')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('next_send_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active');
            $table->timestamps();

            // Reference SendPortal's subscribers table
            $table->foreign('subscriber_id')->references('id')->on('sendportal_subscribers')->onDelete('cascade');
            
            // Ensure unique constraint - one subscriber per sequence
            $table->unique(['subscriber_id', 'email_sequence_id']);
            $table->index(['status', 'next_send_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriber_sequences');
    }
};