<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Vincula um equipamento à Ordem de Serviço (chamado) em que foi usado.
 * FK de verdade aqui é seguro -- diferente de chamados.setor, a tabela
 * equipamentos é 100% nossa, o bot nunca escreve nela.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('equipamentos', 'chamado_id')) {
            Schema::table('equipamentos', function (Blueprint $table) {
                $table->foreignId('chamado_id')->nullable()->after('quantidade')
                    ->constrained('chamados')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('equipamentos', 'chamado_id')) {
            Schema::table('equipamentos', function (Blueprint $table) {
                $table->dropConstrainedForeignId('chamado_id');
            });
        }
    }
};
