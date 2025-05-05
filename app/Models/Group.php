<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection; 

class Group extends Model
{
    use HasFactory;

    // Указываем имя таблицы
    protected $table = 'groups';

    // Отключаем автоматическое управление временными метками created_at и updated_at,
    // так как их нет в нашей таблице
    public $timestamps = false;

    /**
     * Отношение "один ко многим" (обратное): родительская группа.
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        // Связь с той же моделью Group по ключу id_parent -> id
        return $this->belongsTo(Group::class, 'id_parent');
    }

    /**
     * Отношение "один ко многим": дочерние группы.
     * @return HasMany
     */
    public function children(): HasMany
    {
        // Связь с той же моделью Group по ключу id -> id_parent
        return $this->hasMany(Group::class, 'id_parent');
    }

    /**
     * Отношение "один ко многим": товары в этой группе.
     * @return HasMany
     */
    public function products(): HasMany
    {
        // Связь с моделью Product по ключу id -> id_group
        return $this->hasMany(Product::class, 'id_group');
    }

    // --- Методы для получения всех подгрупп и подсчета товаров ---

    /**
     * Получить все ID дочерних групп рекурсивно.
     * @return Collection
     */
    public function getAllChildrenIds(): Collection
    {
        $ids = collect([$this->id]); // Начинаем с ID текущей группы
        $children = $this->children()->with('children')->get(); // Загружаем детей с их детьми

        foreach ($children as $child) {
            $ids = $ids->merge($child->getAllChildrenIds()); // Рекурсивно объединяем ID
        }

        return $ids;
    }

    /**
     * Получить общее количество товаров в этой группе и всех ее подгруппах.
     * @return int
     */
    public function getTotalProductCount(): int
    {
        $groupIds = $this->getAllChildrenIds();
        // Считаем продукты, у которых id_group находится в полученном списке ID
        return Product::whereIn('id_group', $groupIds)->count();
    }

    /**
     * Получить хлебные крошки для группы.
     * @return Collection
     */
    public function getBreadcrumbs(): Collection
    {
        $breadcrumbs = collect();
        $group = $this; // Начинаем с текущей группы

        // Поднимаемся вверх по родителям, пока не дойдем до корневого элемента
        while ($group) {
            // Добавляем в начало коллекции, чтобы порядок был правильный
            $breadcrumbs->prepend($group);
            // Переходим к родителю
            // Используем `parent()->first()` чтобы получить модель родителя или null
            $group = $group->parent()->first();
        }
        return $breadcrumbs;
    }
}
