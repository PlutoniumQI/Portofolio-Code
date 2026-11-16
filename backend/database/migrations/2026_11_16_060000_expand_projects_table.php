<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the fields required by the public project pages: a stable slug,
     * English copy, a case-study body, gallery images, repository links, and
     * the featured flag used on the home page.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
            $table->string('title_en')->nullable()->after('title');
            $table->string('category')->nullable()->index()->after('title_en');
            $table->text('excerpt_id')->nullable()->after('description');
            $table->text('excerpt_en')->nullable()->after('excerpt_id');
            $table->text('description_en')->nullable()->after('excerpt_en');
            $table->longText('content_id')->nullable()->after('description_en');
            $table->longText('content_en')->nullable()->after('content_id');
            $table->json('gallery')->nullable()->after('image_url');
            $table->string('image_disk')->nullable()->after('gallery');
            $table->string('repo_url')->nullable()->after('url');
            $table->boolean('is_featured')->default(false)->index()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug',
                'title_en',
                'category',
                'excerpt_id',
                'excerpt_en',
                'description_en',
                'content_id',
                'content_en',
                'gallery',
                'image_disk',
                'repo_url',
                'is_featured',
            ]);
        });
    }
};
