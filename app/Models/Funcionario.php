<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Funcionario extends Model
{
    use HasFactory;

    public function endereco(): MorphOne
    {
        return $this->morphOne(Endereco::class, 'enderecoable');
    }

    public function escolaridade(): BelongsTo
    {
        return $this->belongsTo(Escolaridade::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->BelongsTo(Cargo::class);
    }

    public function os(): HasMany
    {
        return $this->hasMany(Os::class);
    }
}
