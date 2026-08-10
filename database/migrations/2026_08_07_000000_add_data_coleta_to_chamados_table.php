<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "data_coleta" (collection date) is another dashboard-only field, same
 * spirit as prazo/prioridade: the bot never sets it, so it's nullable and
 * safe to add on top of the shared table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->date('data_coleta')->nullable()->after('prioridade');
        });
    }

    public function down(): void
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->dropColumn('data_coleta');
        });
    }
};
