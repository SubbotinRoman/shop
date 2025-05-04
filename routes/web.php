<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

// Главная страница каталога (список корневых групп и всех товаров)
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');

// Страница группы товаров (список подгрупп и товаров группы)
// {group} - параметр маршрута. Laravel попытается найти модель Group по ID.
Route::get('/group/{group}', [CatalogController::class, 'showGroup'])->name('catalog.group');

// Страница товара (карточка товара)
// {product} - параметр маршрута. Laravel попытается найти модель Product по ID.
Route::get('/product/{product}', [CatalogController::class, 'showProduct'])->name('catalog.product');
