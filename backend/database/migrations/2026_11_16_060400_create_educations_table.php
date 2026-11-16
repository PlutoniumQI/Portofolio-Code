<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->string('institution');
            $table->string('degree_id')->nullable();
            $table->string('degree_en')->nullable();
            $table->string('field_id')->nullable();
            $table->string('field_en')->nullable();
            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educations');
    }
};
