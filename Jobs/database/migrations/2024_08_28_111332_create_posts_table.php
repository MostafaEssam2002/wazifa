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
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Creates an 'id' column as the primary key
            $table->string('title'); // Creates a 'title' column
            $table->text('content'); // Creates a 'content' column for the post body
            $table->unsignedBigInteger('user_id'); // Foreign key for the user who created the post
            $table->timestamps(); // Creates 'created_at' and 'updated_at' columns
            // Optionally, add a foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
