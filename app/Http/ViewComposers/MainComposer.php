<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class MainComposer
{

    /**
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $crmUser  = \App::make('CRMUser');
        $docsApi  = \App::make('DocsApi');

        // Получаем список продуктов пользователя
        $products = $crmUser->getProducts('enabled', env('CLEAR_PRODUCTS_CACHE', false));

        // Запросы к апи доксов через обортку для кэширования, если есть в кэше берем оттуда
        $sortedProducts = $docsApi->cachedQuery("sorted_products_" . serialize($products), function() use ($docsApi, $products) {
            return $docsApi->sortProducts($products);
        });

        // Формируем карту продукт - раздел
        $map = [];
        foreach($products as $i) {
            foreach($sortedProducts as $index=>$section) {
                if(isset($section['products'][$i])) {
                    if(!isset($map[$i])) {
                        $map[$i] = [];
                    }
                    if(!in_array($index, $map[$i])) {
                        $map[$i][] = $index;
                    }
                }
            }
        }
        // Формируем карту раздел - продукт
        $sections = [];
        foreach($sortedProducts as $k=>$i) {
            if(!empty($i['products'])) {
                $sections[$k] = array_keys($i['products']);
            }
        }

        // Получаем выбранные продукты
        $selected = \Input::has('p') ? \Input::get('p') : [];

        //$documents = $docsApi->documentsByProducts($products);
        $view->with('sorted_products', $sortedProducts);
        $view->with('map_products', $map);
        $view->with('map_sections', $sections);
        $view->with('map_selected', $selected);

    }
}