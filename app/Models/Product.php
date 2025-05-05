<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    public $timestamps = false;
    // protected $fillable = ['id_group', 'name'];

    /**
     * Отношение "один ко многим" (обратное): группа, к которой принадлежит товар.
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        // Связь с моделью Group по ключу id_group -> id
        return $this->belongsTo(Group::class, 'id_group');
    }

    /**
     * Отношение "один к одному": цена товара.
     * @return HasOne
     */
    public function price(): HasOne
    {
        // Связь с моделью Price по ключу id_product -> id (в таблице products)
        // т.е. ищем запись в prices, где prices.id_product = products.id
        return $this->hasOne(Price::class, 'id_product');
    }

    /**
     * Получить хлебные крошки для товара (через его группу).
     * @return \Illuminate\Support\Collection
     */
    public function getBreadcrumbs(): \Illuminate\Support\Collection
    {
        // Если у товара есть группа, получаем ее хлебные крошки
        if ($this->group) {
            return $this->group->getBreadcrumbs();
        }
        // Возвращаем пустую коллекцию, если группы нет
        return collect();
    }
}
