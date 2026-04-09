<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uids', function (Blueprint $table) {
            $table->enum('tipe_data', ['internal', 'klhk'])->default('internal')->after('lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('uids', function (Blueprint $table) {
            $table->dropColumn('tipe_data');
        });
    }
};
