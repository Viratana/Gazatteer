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
        Schema::create('location_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('parent_id')->nullable()->constrained('location_codes', 'id')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('code', 12)->unique();
            $table->string('postal_code', 14)->nullable()->unique();
            $table->text('reference')->nullable();
            $table->string('coordination')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->default(1);
            $table->string('created_by', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_codes');
    }
};
