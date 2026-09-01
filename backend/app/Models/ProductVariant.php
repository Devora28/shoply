<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $guarded = [];
    protected function casts(): array
    {
        return [
            'attributes' => 'array',
            'is_active' => 'boolean',
        ];
    }
    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }
}
