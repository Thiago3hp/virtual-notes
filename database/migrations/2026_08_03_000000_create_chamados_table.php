<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * IMPORTANT: this table is shared with the T.I-Sesa-bot WhatsApp bot
 * (src/config/db.js -> ensureSchema()). The bot talks to MySQL directly
 * via mysql2/raw SQL, not through this Laravel app, so the column names
 * and types below must stay identical to what the bot creates. Whichever
 * side boots first wins (both use CREATE TABLE IF NOT EXISTS in spirit);
 * from then on both read/write the same rows.
 *
 * Do not rename these columns without updating T.I-Sesa-bot too.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chamados')) {
            return;
        }

        Schema::create('chamados', function (Blueprint $table) {
            // Alinhado com o padrão do MySQL 8 (o que a conexão raw do
            // bot usa, já que ele não especifica charset/collation nos
            // CREATE TABLE dele). Isso só importa se essa migration
            // chegar a rodar de verdade (ambiente novo, sem o bot ter
            // criado a tabela antes) -- evita reintroduzir o erro
            // "Illegal mix of collations" que aconteceu com clientes.nome.
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->id();
            $table->string('solicitante_nome')->nullable();
            $table->string('setor', 100);
            $table->string('problema');
            $table->text('descricao')->nullable();
            $table->string('sala')->nullable();
            $table->string('solicitante_numero', 20);
            $table->string('numero_adicional', 20)->nullable();
            $table->enum('status', ['aberto', 'em_andamento', 'fechado'])->default('aberto');
            $table->dateTime('criado_em')->useCurrent();
            $table->dateTime('fechado_em')->nullable();
            $table->string('tecnico_nome')->nullable();
            $table->text('laudo_tecnico')->nullable();
            $table->tinyInteger('avaliacao')->nullable();
            $table->string('solicitante_jid', 60)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chamados');
    }
};
