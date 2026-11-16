<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Experiences now cover work, organisations, competitions, and the PLK
     * internship. English copy keeps the timeline bilingual.
     */
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('type')->default('work')->index()->after('id');
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
            $table->string('location')->nullable()->after('description_en');
            $table->string('url')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['type', 'title_en', 'description_en', 'location', 'url']);
        });
    }
};
