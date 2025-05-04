@extends('layouts.app')

@section('content')
    {{-- Включаем хлебные крошки, передавая $breadcrumbs и $product --}}
    @include('catalog.partials._breadcrumbs', ['breadcrumbs' => $breadcrumbs, 'product' => $product])

    <div class="card">
        <div class="card-body">
            {{-- Название товара --}}
            <h1 class="card-title">{{ $product->name }}</h1>

            {{-- Цена товара --}}
            @if ($product->price)
                <p class="card-text fs-4"> <!-- Увеличиваем размер шрифта цены -->
                    <strong>Цена:</strong> {{ number_format($product->price->price, 0, '.', ' ') }} руб.
                </p>
            @else
                <p class="card-text fs-4"><strong>Цена:</strong> не указана</p>
            @endif

            {{-- Здесь можно добавить другое описание товара, характеристики и т.д., если они будут --}}
            {{-- <p class="card-text">Описание товара...</p> --}}
        </div>
        {{-- Можно добавить футер карточки, например, с кнопкой "В корзину" --}}
        {{-- <div class="card-footer">
            <button class="btn btn-primary">Добавить в корзину</button>
        </div> --}}
    </div>
@endsection
