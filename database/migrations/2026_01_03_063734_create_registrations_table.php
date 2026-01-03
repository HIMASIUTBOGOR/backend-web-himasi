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
        Schema::create('registrations', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('fullname');
            $table->string('nim', 10)->unique();
            $table->integer('semester',2);
            $table->string('no_wa',15);
            $table->foreignUuid('department_id')
            ->nullable()
            ->constrained('departemens')
            ->onDelete('cascade');
            $table->longText('reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
