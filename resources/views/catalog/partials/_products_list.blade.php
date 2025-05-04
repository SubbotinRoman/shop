{{-- Принимает $products - объект пагинации товаров --}}
{{-- Принимает $currentSortBy, $currentSortDir - текущие параметры сортировки --}}
{{-- Принимает $baseUrl - базовый URL для ссылок сортировки (либо текущий URL группы, либо главной страницы) --}}

@php
    // Функция для генерации URL сортировки
    // Сохраняет все текущие query параметры, кроме sort_by и sort_dir, и добавляет новые
    function sortUrl($baseUrl, $sortBy, $sortDir) {
        $query = request()->except(['sort_by', 'sort_dir', 'page']); // Убираем старые sort и page
        $query['sort_by'] = $sortBy;
        $query['sort_dir'] = $sortDir;
        return $baseUrl . '?' . http_build_query($query); // Строим новый URL
    }

    // Определяем стрелочки для индикации сортировки
    $arrowAsc = '↑';
    $arrowDesc = '↓';
@endphp

<div class="sorting-controls mb-3">
    Сортировать:
    <a href="{{ sortUrl($baseUrl, 'price', 'asc') }}"
       class="text-decoration-none {{ $currentSortBy == 'price' && $currentSortDir == 'asc' ? 'fw-bold' : '' }}">
        По цене {!! $currentSortBy == 'price' && $currentSortDir == 'asc' ? $arrowAsc : '' !!}
    </a> |
    <a href="{{ sortUrl($baseUrl, 'price', 'desc') }}"
       class="text-decoration-none {{ $currentSortBy == 'price' && $currentSortDir == 'desc' ? 'fw-bold' : '' }}">
        По цене {!! $currentSortBy == 'price' && $currentSortDir == 'desc' ? $arrowDesc : '' !!}
    </a> |
    <a href="{{ sortUrl($baseUrl, 'name', 'asc') }}"
       class="text-decoration-none {{ $currentSortBy == 'name' && $currentSortDir == 'asc' ? 'fw-bold' : '' }}">
        По названию {!! $currentSortBy == 'name' && $currentSortDir == 'asc' ? $arrowAsc : '' !!}
    </a> |
    <a href="{{ sortUrl($baseUrl, 'name', 'desc') }}"
       class="text-decoration-none {{ $currentSortBy == 'name' && $currentSortDir == 'desc' ? 'fw-bold' : '' }}">
        По названию {!! $currentSortBy == 'name' && $currentSortDir == 'desc' ? $arrowDesc : '' !!}
    </a>
</div>

@if (isset($products) && $products->count() > 0)
    <div class="list-group mb-3">
        @foreach ($products as $product)
            <a href="{{ route('catalog.product', $product->id) }}" class="list-group-item list-group-item-action">
                {{ $product->name }}
                {{-- Отображаем цену, если она есть (мы ее загружали через with('price')) --}}
                @if ($product->price)
                    - {{ number_format($product->price->price, 0, '.', ' ') }} руб.
                @else
                    - (цена не указана)
                @endif
            </a>
        @endforeach
    </div>

    {{-- Ссылки пагинации Bootstrap --}}
    {{ $products->links('pagination::bootstrap-5') }}

@else
    <p>Товаров не найдено.</p>
@endif
