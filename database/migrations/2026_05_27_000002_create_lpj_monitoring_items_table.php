<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_monitoring_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_monitoring_group_id')
                ->constrained('lpj_monitoring_groups')
                ->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
            $table->text('detail_kegiatan');
            $table->string('pic');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_monitoring_items');
    }
};
