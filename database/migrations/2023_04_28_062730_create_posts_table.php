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
            $table->id();
            $table->unsignedBigInteger('post_type');
            $table->string('title');
            $table->text('description');
            $table->string('image');
            $table->string('url');
            $table->enum('status', ['WAITING', 'ACTIVE', 'CLOSED'])->default('WAITING');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('post_type')->references('id')->on('category_post')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
