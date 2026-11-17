<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the retired percentage column. Run this only after the release that
     * reads `level` is live, so no instance still selects `percentage`.
     */
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('percentage');
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->unsignedTinyInteger('percentage')->default(0)->after('category');
        });

        DB::table('skills')->where('level', 'expert')->update(['percentage' => 95]);
        DB::table('skills')->where('level', 'advanced')->update(['percentage' => 85]);
        DB::table('skills')->where('level', 'intermediate')->update(['percentage' => 75]);
        DB::table('skills')->where('level', 'beginner')->update(['percentage' => 60]);
    }
};
