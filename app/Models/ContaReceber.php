<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContaReceber extends Model
{
    use HasFactory;

    public function os(): BelongsTo
    {
        return $this->belongsTo(Os::class);
    }
}
