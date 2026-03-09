<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname', 100)->after('name');
            $table->string('lastname', 100)->after('firstname');
            $table->string('phone', 20)->after('email');
            $table->string('gender', 10)->nullable()->after('phone');
            $table->string('marital_status', 20)->nullable()->after('gender');
            $table->date('date_of_birth')->nullable()->after('marital_status');
            $table->string('residence', 100)->nullable()->after('date_of_birth');
            $table->string('local_government_area', 100)->nullable()->after('residence');
            $table->string('state_of_origin', 100)->nullable()->after('local_government_area');
            $table->string('home_town', 100)->nullable()->after('state_of_origin');
            $table->string('nationality', 100)->nullable()->after('home_town');
            $table->string('religion', 50)->nullable()->after('nationality');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'firstname',
                'lastname', 
                'phone',
                'gender',
                'marital_status',
                'date_of_birth',
                'residence',
                'local_government_area',
                'state_of_origin',
                'home_town',
                'nationality',
                'religion'
            ]);
        });
    }
};
