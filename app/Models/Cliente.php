<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome',
    ];

    /**
     * Não existe FK de verdade entre chamados e clientes (a coluna
     * chamados.setor é texto livre, parte do schema compartilhado com o
     * bot), mas o Eloquent permite declarar a relação mesmo assim usando
     * setor como chave estrangeira "lógica" e nome como chave local.
     */
    public function chamados()
    {
        return $this->hasMany(Chamado::class, 'setor', 'nome');
    }
}
