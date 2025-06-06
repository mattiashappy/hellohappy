<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tag_email_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tag_id');
            $table->foreignId('email_sequence_id')->constrained('email_sequences')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['tag_id', 'email_sequence_id']);
            $table->foreign('tag_id')->references('id')->on('sendportal_tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tag_email_sequences');
    }
};
