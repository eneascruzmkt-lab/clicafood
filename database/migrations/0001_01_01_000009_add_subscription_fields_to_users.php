<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('subscription_status', ['active', 'canceled', 'trial', 'expired'])->nullable()->default(null)->after('role');
            $table->string('subscription_id')->nullable()->after('subscription_status');
            $table->string('subscription_plan')->nullable()->after('subscription_id');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_plan');
            $table->string('abacatepay_customer_id')->nullable()->after('subscription_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'subscription_id',
                'subscription_plan',
                'subscription_expires_at',
                'abacatepay_customer_id',
            ]);
        });
    }
};
