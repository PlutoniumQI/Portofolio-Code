<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('title_id');
            $table->string('title_en')->nullable();
            $table->string('issuer');
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->string('image_disk')->nullable();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
