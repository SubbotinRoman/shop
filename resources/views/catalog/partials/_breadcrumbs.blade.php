{{-- Принимает переменную $breadcrumbs - коллекцию моделей Group --}}
{{-- и опционально $product - модель Product (для последней крошки) --}}
@if (isset($breadcrumbs) && $breadcrumbs->count() > 0)
    <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '→';" class="mb-3">
        <ol class="breadcrumb">
            {{-- Ссылка на главную страницу --}}
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Главная</a></li>

            {{-- Проходим по всем группам в хлебных крошках --}}
            @foreach ($breadcrumbs as $breadcrumbGroup)
                {{-- Если это не последняя крошка ИЛИ нет переданной модели продукта --}}
                @if (!$loop->last || !isset($product))
                    {{-- Делаем ссылку на группу --}}
                    <li class="breadcrumb-item">
                        <a href="{{ route('catalog.group', $breadcrumbGroup->id) }}">{{ $breadcrumbGroup->name }}</a>
                    </li>
                @else
                    {{-- Иначе (это последняя группа в крошках для страницы продукта) --}}
                    {{-- делаем ее неактивной (просто текст) --}}
                    <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumbGroup->name }}</li>
                @endif
            @endforeach

            {{-- Если передан продукт, добавляем его название как последнюю активную крошку --}}
            @if (isset($product))
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            @endif
        </ol>
    </nav>
@endif
