<?php

namespace App\Models;

use App\Observers\ChamadoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a row of the shared "chamados" table. This table is created
 * and fed by the T.I-Sesa-bot WhatsApp bot (raw mysql2 inserts) -- new
 * chamados normally appear here without ever going through this app.
 * This model is how the dashboard reads and manages them afterwards
 * (assign a técnico, close with a laudo, set prazo/prioridade, etc).
 *
 * IMPORTANT: because the bot inserts rows with raw SQL (not Eloquent),
 * ChamadoObserver only fires for changes made from this dashboard -- it
 * never sees a chamado the moment the bot creates it.
 */
#[ObservedBy(ChamadoObserver::class)]
class Chamado extends Model
{
    protected $table = 'chamados';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null;

    protected $fillable = [
        'solicitante_nome',
        'setor',
        'problema',
        'descricao',
        'sala',
        'solicitante_numero',
        'numero_adicional',
        'status',
        'fechado_em',
        'tecnico_nome',
        'laudo_tecnico',
        'avaliacao',
        'solicitante_jid',
        'prazo',
        'prioridade',
        'data_coleta',
        'origem_referencia',
    ];

    protected function casts(): array
    {
        return [
            'criado_em' => 'datetime',
            'fechado_em' => 'datetime',
            'prazo' => 'date',
            'data_coleta' => 'date',
            'avaliacao' => 'integer',
        ];
    }
}
