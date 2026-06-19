<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_monitoring_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
            $table->date('tanggal')->nullable();
            $table->string('fase')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_monitoring_groups');
    }
};
