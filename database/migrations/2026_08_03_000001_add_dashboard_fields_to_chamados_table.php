<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "prazo" and "prioridade" don't exist anywhere in the T.I-Sesa-bot
 * codebase -- the bot never sets them. They're managed from this
 * dashboard only (via the "Editar" action), so they're nullable /
 * defaulted and safe to add on top of the shared table: the bot's
 * INSERT statements don't reference these columns, so they keep
 * working unchanged and just leave these two as null/default.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->date('prazo')->nullable()->after('sala');
            $table->enum('prioridade', ['Baixa', 'Normal', 'Alta', 'Urgente'])
                ->default('Normal')
                ->after('prazo');
        });
    }

    public function down(): void
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->dropColumn(['prazo', 'prioridade']);
        });
    }
};
