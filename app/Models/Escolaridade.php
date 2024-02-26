<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Escolaridade extends Model
{
    use HasFactory;

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class);
    }
}
