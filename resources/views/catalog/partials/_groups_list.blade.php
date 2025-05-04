{{-- Принимает переменную $groups - коллекцию моделей Group --}}
{{-- и $level - текущий уровень вложенности (для отступов) --}}
@php $level = $level ?? 0; @endphp

@if (isset($groups) && $groups->count() > 0)
    <ul class="list-unstyled ps-{{ $level > 0 ? 3 : 0 }} groups-list level-{{$level}}">
        @foreach ($groups as $group)
            @php
                $hasChildren = $group->children_count > 0 ?? ($group->relationLoaded('children') ? $group->children->isNotEmpty() : $group->children()->exists());
            @endphp
            <li class="group-item mb-1">
                {{-- Оборачиваем всю строку в ссылку-кнопку, если есть дети --}}
                @if ($hasChildren)
                    <a href="#subgroup-{{ $group->id }}" {{-- Ссылка-якорь на ID блока collapse --}}
                    class="d-flex align-items-center text-decoration-none text-dark toggle-group-link" {{-- Классы для стиля и JS --}}
                       data-bs-toggle="collapse"
                       role="button" {{-- Для доступности --}}
                       aria-expanded="false" {{-- Изначально свернуто --}}
                       aria-controls="subgroup-{{ $group->id }}">

                        {{-- Иконка +/- (теперь внутри основной ссылки) --}}
                        <span class="me-1 group-toggle-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square icon-plus" viewBox="0 0 16 16">
                              <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                              <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-square icon-minus d-none" viewBox="0 0 16 16">
                              <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                              <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"/>
                            </svg>
                        </span>

                        {{-- Название группы --}}
                        <span>{{ $group->name }}</span>

                        {{-- Количество товаров (тоже внутри ссылки) --}}
                        @if (isset($group->total_products_count))
                            <span class="ms-1 text-muted small">({{ $group->total_products_count }})</span>
                        @endif

                        {{-- Ссылка на страницу самой группы (маленькая иконка справа) --}}
                        <a href="{{ route('catalog.group', $group->id) }}"
                           class="ms-auto btn btn-sm btn-outline-secondary p-0 px-1 view-group-link"
                           title="Перейти в категорию {{ $group->name }}"
                           onclick="event.stopPropagation();"> {{-- Предотвращаем схлопывание при клике на эту ссылку --}}
                            > {{-- Или можно использовать иконку --}}
                        </a>
                    </a>
                @else
                    {{-- Если нет детей, просто отображаем строку без функционала сворачивания --}}
                    <div class="d-flex align-items-center">
                        {{-- Заглушка для выравнивания иконки --}}
                        <span class="me-1 group-toggle-placeholder" style="display: inline-block; width: 16px; height: 16px;"></span>
                        {{-- Ссылка на группу (теперь основная ссылка) --}}
                        <a href="{{ route('catalog.group', $group->id) }}" class="text-decoration-none text-dark">{{ $group->name }}</a>
                        {{-- Количество товаров --}}
                        @if (isset($group->total_products_count))
                            <span class="ms-1 text-muted small">({{ $group->total_products_count }})</span>
                        @endif
                    </div>
                @endif

                {{-- Подсписок для дочерних групп (без изменений) --}}
                @if ($hasChildren)
                    <div class="collapse" id="subgroup-{{ $group->id }}">
                        @if($group->relationLoaded('children'))
                            @include('catalog.partials._groups_list', ['groups' => $group->children, 'level' => $level + 1])
                        @else
                            @include('catalog.partials._groups_list', ['groups' => $group->children()->get(), 'level' => $level + 1])
                        @endif
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@endif
