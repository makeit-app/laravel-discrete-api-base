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
        /**
         * WARNING !
         *
         * THIS MIGRATIONS ASSUME TO CLEAN LARAVEL INSTALLATION !
         */
        Schema::dropIfExists('users');
        /**
         * - removed 'name' field
         * - changed bigint to uuid 'id' field
         * - added soft deletes
         */
        Schema::create('users', function (Blueprint $table) {
            // $table->id();
            // $table->string('name')->index();
            $table->uuid('id')->primary();
            $table->boolean('is_banned')->index()->default(false);
            //
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
