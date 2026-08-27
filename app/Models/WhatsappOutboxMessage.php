<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappOutboxMessage extends Model
{
    protected $table = 'whatsapp_outbox';

    protected $fillable = [
        'numero',
        'mensagem',
        'status',
        'enviado_em',
    ];

    protected function casts(): array
    {
        return [
            'enviado_em' => 'datetime',
        ];
    }
}
