<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Documentação API Upload e indexação de arquivo no ElasticSearch",
 *      description="API Upload e indexação de arquivo no ElasticSearch",
 *      @OA\Contact(
 *          email="wanderson@dewtech.io",
 *          name="Dewtech",
 *          url="https://www.dewtech.io"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
