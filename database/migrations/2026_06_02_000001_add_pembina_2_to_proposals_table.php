<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->string('pembina_2_nama')->nullable()->after('pembina_nip');
            $table->string('pembina_2_nip')->nullable()->after('pembina_2_nama');
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['pembina_2_nama', 'pembina_2_nip']);
        });
    }
};
