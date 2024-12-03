<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clé étrangère vers users
            $table->text('content'); // Contenu de la publication
            $table->timestamps();
            $table->string('image_path')->nullable(); 

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
