<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request; // Используется для получения данных из запроса (параметры сортировки)

class CatalogController extends Controller
{
    const PRODUCTS_PER_PAGE = 6; // Константа для количества товаров на странице

    /**
     * Главная страница каталога.
     * Показывает корневые группы и ВСЕ товары с пагинацией и сортировкой.
     */
    public function index(Request $request)
    {
        // 1. Получаем корневые группы (id_parent = 0)
        $rootGroups = Group::where('id_parent', 0)->get();

        // 2. Загружаем количество товаров для каждой корневой группы
        //    Это может быть не очень эффективно, если групп много.
        //    В реальном проекте можно оптимизировать (кеширование, денормализация).
        foreach ($rootGroups as $group) {
            $group->total_products_count = $group->getTotalProductCount();
        }

        // 3. Получаем все товары с их ценами (используем жадную загрузку 'price')
        $productsQuery = Product::with('price'); // Начинаем строить запрос

        // 4. Применяем сортировку
        $sortBy = $request->input('sort_by', 'name'); // По умолчанию сортируем по имени
        $sortDir = $request->input('sort_dir', 'asc'); // По умолчанию по возрастанию

        if ($sortBy == 'price' && $sortDir == 'asc') {
            // Сортировка по цене (возрастание): JOIN с prices и сортировка по price
            $productsQuery->join('prices', 'products.id', '=', 'prices.id_product')
                ->orderBy('prices.price', 'asc')
                ->select('products.*'); // Важно выбрать поля products, чтобы избежать конфликтов имен id
        } elseif ($sortBy == 'price' && $sortDir == 'desc') {
            // Сортировка по цене (убывание)
            $productsQuery->join('prices', 'products.id', '=', 'prices.id_product')
                ->orderBy('prices.price', 'desc')
                ->select('products.*');
        } elseif ($sortBy == 'name' && $sortDir == 'asc') {
            // Сортировка по имени (возрастание)
            $productsQuery->orderBy('products.name', 'asc');
        } elseif ($sortBy == 'name' && $sortDir == 'desc') {
            // Сортировка по имени (убывание)
            $productsQuery->orderBy('products.name', 'desc');
        } else {
            // По умолчанию, если параметры некорректны
            $productsQuery->orderBy('products.name', 'asc');
        }

        // 5. Применяем пагинацию
        $products = $productsQuery->paginate(self::PRODUCTS_PER_PAGE)
            ->appends($request->query()); // Добавляем параметры сортировки к ссылкам пагинации

        // 6. Передаем данные в представление
        return view('catalog.index', [
            'groups' => $rootGroups,
            'products' => $products,
            'currentSortBy' => $sortBy,  // Передаем текущие параметры сортировки
            'currentSortDir' => $sortDir, // для отображения активных ссылок
        ]);
    }

    /**
     * Страница группы товаров.
     * Показывает подгруппы и товары ВНУТРИ этой группы и ее подгрупп.
     */
    public function showGroup(Request $request, Group $group) // Laravel автоматически найдет Group по ID из URL
    {
        // 1. Загружаем дочерние группы текущей группы
        $subgroups = $group->children()->get();

        // 2. Загружаем количество товаров для каждой подгруппы
        foreach ($subgroups as $subgroup) {
            $subgroup->total_products_count = $subgroup->getTotalProductCount();
        }

        // 3. Получаем ID всех дочерних групп (включая текущую)
        $groupIds = $group->getAllChildrenIds();

        // 4. Получаем товары, принадлежащие этим группам, с их ценами
        $productsQuery = Product::whereIn('id_group', $groupIds)->with('price');

        // 5. Применяем сортировку (логика та же, что и в index)
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc');

        if ($sortBy == 'price' && $sortDir == 'asc') {
            $productsQuery->join('prices', 'products.id', '=', 'prices.id_product')
                ->orderBy('prices.price', 'asc')
                ->select('products.*');
        } elseif ($sortBy == 'price' && $sortDir == 'desc') {
            $productsQuery->join('prices', 'products.id', '=', 'prices.id_product')
                ->orderBy('prices.price', 'desc')
                ->select('products.*');
        } elseif ($sortBy == 'name' && $sortDir == 'asc') {
            $productsQuery->orderBy('products.name', 'asc');
        } elseif ($sortBy == 'name' && $sortDir == 'desc') {
            $productsQuery->orderBy('products.name', 'desc');
        } else {
            $productsQuery->orderBy('products.name', 'asc');
        }

        // 6. Применяем пагинацию
        $products = $productsQuery->paginate(self::PRODUCTS_PER_PAGE)
            ->appends($request->query());

        // 7. Получаем хлебные крошки для текущей группы
        $breadcrumbs = $group->getBreadcrumbs();

        // 8. Передаем данные в представление
        return view('catalog.group', [
            'currentGroup' => $group,
            'subgroups' => $subgroups,
            'products' => $products,
            'breadcrumbs' => $breadcrumbs,
            'currentSortBy' => $sortBy,
            'currentSortDir' => $sortDir,
        ]);
    }

    /**
     * Страница карточки товара.
     */
    public function showProduct(Product $product) // Laravel автоматически найдет Product по ID
    {
        // 1. Загружаем связанные данные (цену и группу), если они еще не загружены
        $product->loadMissing('price', 'group');

        // 2. Получаем хлебные крошки для товара (через его группу)
        $breadcrumbs = $product->getBreadcrumbs();

        // 3. Передаем данные в представление
        return view('catalog.product', [
            'product' => $product,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
