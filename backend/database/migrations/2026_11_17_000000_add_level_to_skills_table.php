<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the qualitative skill level and map the old percentage bands onto it.
     *
     * This runs before the backend that reads `level` is deployed, so the old
     * release keeps working while the column is added and backfilled.
     */
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('level', 20)->default('intermediate')->after('category');
        });

        DB::table('skills')->where('percentage', '>=', 90)->update(['level' => 'expert']);
        DB::table('skills')->whereBetween('percentage', [80, 89])->update(['level' => 'advanced']);
        DB::table('skills')->whereBetween('percentage', [70, 79])->update(['level' => 'intermediate']);
        DB::table('skills')->where('percentage', '<', 70)->update(['level' => 'beginner']);
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
