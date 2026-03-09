<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the old name field
            $table->dropColumn('name');
            
            // Make new fields required
            $table->string('firstname', 100)->nullable(false)->change();
            $table->string('lastname', 100)->nullable(false)->change();
            $table->string('phone', 20)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back the old name field
            $table->string('name')->after('id');
            
            // Make fields nullable again
            $table->string('firstname', 100)->nullable()->change();
            $table->string('lastname', 100)->nullable()->change();
            $table->string('phone', 20)->nullable()->change();
        });
    }
};
