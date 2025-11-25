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
            $table->foreignId('location_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('location_codes', 'id')->cascadeOnDelete();
            $table->string('code', 12);
            $table->string('name_kh', 100);
            $table->string('name_en', 80)->nullable();
            $table->string('postal_code', 14)->nullable();
            $table->text('reference')->nullable();
            $table->string('coordination')->nullable();
            $table->text('note')->nullable();
            $table->text('note_by_checker')->nullable();
            $table->string('created_by', 50)->nullable();
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
