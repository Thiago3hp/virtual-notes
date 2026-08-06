<?php

namespace App\Mediators;

use App\Http\Resources\ChamadoResource;
use App\Services\ChamadoService;
use Illuminate\Support\Facades\Validator;

/**
 * Mediator pattern.
 *
 * Closing a chamado is a small coordinated action (set status, stamp
 * fechado_em, record técnico + laudo) -- the same thing the bot's own
 * "tecnico" flow does over WhatsApp (see closeTicketWithReport in
 * ticketService.js), just triggered from the dashboard instead. Controllers
 * only talk to this mediator; it decides how to call ChamadoService so the
 * "close" business rule lives in one place instead of being duplicated
 * across every controller that might need to close a ticket.
 */
class ChamadoMediator
{
    public function __construct(private readonly ChamadoService $chamados)
    {
    }

    public function create(array $dados): ChamadoResource
    {
        $chamado = $this->chamados->create($dados);

        return new ChamadoResource($chamado);
    }

    public function update(int $id, array $dados): ChamadoResource
    {
        $chamado = $this->chamados->update($id, $dados);

        return new ChamadoResource($chamado);
    }

    public function close(int $id, array $dados): ChamadoResource
    {
        $data = Validator::make($dados, [
            'tecnico_nome' => 'required|string|max:255',
            'laudo_tecnico' => 'required|string',
        ])->validate();

        $chamado = $this->chamados->closeWithReport($id, $data['tecnico_nome'], $data['laudo_tecnico']);

        return new ChamadoResource($chamado);
    }
}
