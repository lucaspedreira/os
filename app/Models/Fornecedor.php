<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';

    public function endereco(): MorphOne
    {
        return $this->morphOne(Endereco::class, 'enderecoable');
    }
}
