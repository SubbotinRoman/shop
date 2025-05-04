@extends('layouts.app') {{-- Наследуем основной шаблон --}}

@section('content') {{-- Определяем секцию 'content' --}}
<div class="row">
    {{-- Левая колонка для групп --}}
    <div class="col-md-3">
        <h2>Категории</h2>
        @include('catalog.partials._groups_list', ['groups' => $groups]) {{-- Включаем шаблон списка групп --}}
    </div>

    {{-- Правая колонка для товаров --}}
    <div class="col-md-9">
        <h2>Все товары</h2>
        {{-- Включаем шаблон списка товаров --}}
        {{-- Передаем объект пагинации, текущую сортировку и базовый URL для ссылок сортировки --}}
        @include('catalog.partials._products_list', [
            'products' => $products,
            'currentSortBy' => $currentSortBy,
            'currentSortDir' => $currentSortDir,
            'baseUrl' => route('catalog.index') // URL главной страницы
        ])
    </div>
</div>
@endsection
