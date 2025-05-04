@extends('layouts.app')

@section('content')
    {{-- Включаем хлебные крошки, передавая коллекцию $breadcrumbs --}}
    @include('catalog.partials._breadcrumbs', ['breadcrumbs' => $breadcrumbs])

    <h1>{{ $currentGroup->name }}</h1> {{-- Название текущей группы --}}

    <div class="row mt-4">
        {{-- Левая колонка для подгрупп (если есть) --}}
        @if ($subgroups->count() > 0)
            <div class="col-md-3">
                <h3>Подкатегории</h3>
                {{-- Включаем список групп для подкатегорий $subgroups --}}
                @include('catalog.partials._groups_list', ['groups' => $subgroups])
            </div>
        @endif

        {{-- Правая (или основная, если нет подгрупп) колонка для товаров --}}
        {{-- Используем тернарный оператор для установки класса колонки --}}
        <div class="{{ $subgroups->count() > 0 ? 'col-md-9' : 'col-12' }}">
            <h3>Товары в категории</h3>
            {{-- Включаем список товаров --}}
            {{-- Передаем пагинацию, сортировку и URL ТЕКУЩЕЙ группы как базовый --}}
            @include('catalog.partials._products_list', [
                'products' => $products,
                'currentSortBy' => $currentSortBy,
                'currentSortDir' => $currentSortDir,
                'baseUrl' => route('catalog.group', $currentGroup->id) // URL страницы группы
            ])
        </div>
    </div>
@endsection
