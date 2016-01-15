<?php  namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Services\Upload;

class ApiController extends Controller
{
    public function anyList()
    {
        $products = \Input::get('products', []);
        $offset   = \Input::get('offset', 0);
        $limit    = \Input::get('limit', 0);
        $category = \Input::get('category', 0);
        $query    = \Input::get('query', '');

        $docsApi  = \App::make('DocsApi');

        // отправляем запрос поиска
        $response = $docsApi->cachedQuery("products_list_" . serialize($products) . "l=". $limit . 'o=' . $offset . 'c=' .$category . 'q=' . $query,
            function() use ($docsApi, $products, $category, $query, $offset, $limit) {
                if($query) {
                    // Если текстовый запрос query не пустой
                    return $docsApi->searchDocuments($query, $products, $category, $offset, $limit);
                } else {
                    // Поиск по продуктам и категории
                    return $docsApi->documentsByProducts($products, $category, $offset, $limit);
                }
            });
        // Получаем список документов через кэшированный запрос
        return response()->json($response);
    }

    /*
     *  Получаем одноразовый ключ для скачивания документа
     */
    public function getKey()
    {
        $document_id = \Input::get('document', false);
        try {
            // Получим ключ
            $key = Upload::getUploadKey($document_id);
            return Response::json(['key' => $key], 200);
        } catch (Exception $ex) {
            return Response::json(["error" => $ex->getMessage()], 400);
        }
    }
}
