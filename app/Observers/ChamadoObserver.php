<?php

namespace App\Observers;

use App\Models\Chamado;
use Illuminate\Support\Facades\Log;

/**
 * Observer pattern: reacts to Chamado lifecycle events triggered from this
 * dashboard (created/updated/deleted via Eloquent). Rows inserted directly
 * by the WhatsApp bot bypass Eloquent entirely, so this observer only ever
 * sees dashboard-side actions (e.g. a técnico closing a ticket, editing
 * prazo/prioridade). Kept for the same reason as before: whoever changes a
 * Chamado from the dashboard shouldn't have to remember to log it by hand.
 */
class ChamadoObserver
{
    public function created(Chamado $chamado): void
    {
        Log::info('Chamado created from dashboard', ['id' => $chamado->id, 'setor' => $chamado->setor]);
    }

    public function updated(Chamado $chamado): void
    {
        Log::info('Chamado updated', ['id' => $chamado->id, 'changes' => $chamado->getChanges()]);
    }

    public function deleted(Chamado $chamado): void
    {
        Log::info('Chamado deleted', ['id' => $chamado->id]);
    }
}
