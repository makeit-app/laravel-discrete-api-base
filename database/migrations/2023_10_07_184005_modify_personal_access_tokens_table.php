<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn(['tokenable_id', 'tokenable_type']);
        });
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('tokenable_id');
            $table->string('tokenable_type');
        });
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('client_type')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn(['tokenable_id', 'tokenable_type', 'client_type']);
        });
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->morphs('tokenable');
        });
    }
};
