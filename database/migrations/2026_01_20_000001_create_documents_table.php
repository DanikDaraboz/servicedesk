<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(Str::uuid());
            $table->string('original_filename');
            $table->string('original_pdf_path');
            $table->string('signature_image_path')->nullable();
            $table->string('signed_pdf_path')->nullable();
            $table->integer('page_number')->default(1);
            $table->decimal('position_x', 10, 2)->default(0);
            $table->decimal('position_y', 10, 2)->default(0);
            $table->decimal('signature_width', 10, 2)->default(100);
            $table->decimal('signature_height', 10, 2)->default(50);
            $table->decimal('opacity', 3, 2)->default(1.0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};