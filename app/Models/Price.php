<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;

    protected $table = 'prices';
    public $timestamps = false;
    // protected $fillable = ['id_product', 'price'];

    /**
     * Отношение "один к одному" (обратное): товар, к которому относится цена.
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        // Связь с моделью Product по ключу id_product -> id
        return $this->belongsTo(Product::class, 'id_product');
    }
}
