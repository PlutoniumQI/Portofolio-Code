<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('headline_id')->nullable();
            $table->string('headline_en')->nullable();
            $table->text('intro_id')->nullable();
            $table->text('intro_en')->nullable();
            $table->longText('about_id')->nullable();
            $table->longText('about_en')->nullable();
            $table->string('location')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('availability_id')->nullable();
            $table->string('availability_en')->nullable();
            $table->string('avatar_path')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('avatar_disk')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('cv_url')->nullable();
            $table->string('cv_disk')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('website_url')->nullable();
            $table->unsignedTinyInteger('years_learning')->default(1);
            $table->unsignedTinyInteger('tools_mastered')->default(5);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
