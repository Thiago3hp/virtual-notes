<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $table = 'equipamentos';

    protected $fillable = [
        'nome',
        'descricao',
        'quantidade',
        'chamado_id',
    ];

    protected function casts(): array
    {
        return [
            'quantidade' => 'integer',
        ];
    }

    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }
}
