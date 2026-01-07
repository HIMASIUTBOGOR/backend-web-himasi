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
        Schema::create('news', function (Blueprint $table) {
            $table->uuid('id')->primary();
             $table->foreignUuid('category_id')->constrained('enumerations')->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('desc');
            $table->string('author');
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
