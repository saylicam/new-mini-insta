<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clé étrangère vers users
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Clé étrangère vers posts
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};

