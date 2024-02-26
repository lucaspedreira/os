<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    public function produtos(): HasMany
    {
        return $this->HasMany(Produto::class);
    }

    public function os(): BelongsTo
    {
        return $this->belongsTo(Os::class);
    }
}
