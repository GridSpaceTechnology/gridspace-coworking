<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_host')->default(false);
            $table->text('body');
            $table->timestamps();

            $table->index(['inquiry_id', 'created_at']);
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->timestamp('guest_last_read_at')->nullable()->after('contacted');
            $table->timestamp('host_last_read_at')->nullable()->after('guest_last_read_at');
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['guest_last_read_at', 'host_last_read_at']);
        });

        Schema::dropIfExists('chat_messages');
    }
};
