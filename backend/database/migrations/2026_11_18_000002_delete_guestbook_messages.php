<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The guestbook was retired in favour of moderated testimonial
     * submissions. Delete the old public entries permanently.
     */
    public function up(): void
    {
        DB::table('messages')->where('type', 'guestbook')->delete();
    }

    public function down(): void
    {
        // Deleted guestbook entries cannot be restored.
    }
};
