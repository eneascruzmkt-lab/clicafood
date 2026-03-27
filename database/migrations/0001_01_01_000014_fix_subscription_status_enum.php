<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY subscription_status ENUM('active', 'canceled', 'trial', 'expired', 'pending') NULL DEFAULT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY subscription_status ENUM('active', 'canceled', 'trial', 'expired') NULL DEFAULT NULL");
    }
};
