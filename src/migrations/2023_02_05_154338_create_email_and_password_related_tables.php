<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('token');
            $table->timestampTz('expires_at')->default(now()->addMinutes(config('auth.expirations.email_verifications'))->toDateTimeLocalString());
        });

        Schema::create('email_changes', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('token');
            $table->foreignUuid('user_id')->unique();
            $table->timestamp('expires_at')->default(now()->addMinutes(config('auth.expirations.email_changes')));
        });

        Schema::create('password_changes', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('token');
            $table->foreignUuid('user_id')->unique();
            $table->timestamp('expires_at')->default(now()->addMinutes(config('auth.expirations.password_changes')));
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('token');
            $table->foreignUuid('user_id')->unique();
            $table->timestamp('expires_at')->default(now()->addMinutes(config('auth.expirations.password_resets')));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('email_verifications');
        Schema::dropIfExists('password_changes');
        Schema::dropIfExists('email_changes');
        Schema::dropIfExists('password_resets');
    }
};
