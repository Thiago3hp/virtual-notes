<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamentos extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'quantidade',
    ];
}
