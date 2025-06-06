<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sequence_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_sequence_id')->constrained('email_sequences')->onDelete('cascade');
            $table->unsignedInteger('template_id')->nullable();
            $table->integer('delay_days')->default(0);
            $table->integer('send_order');
            $table->string('subject');
            $table->timestamps();

            // Reference SendPortal's templates table
            $table->foreign('template_id')->references('id')->on('sendportal_templates')->onDelete('set null');
            $table->index(['email_sequence_id', 'send_order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sequence_emails');
    }
};