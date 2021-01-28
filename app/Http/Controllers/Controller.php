<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


 /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Users System Api  Documentation ",
     *      description="This documentation for user system this system hav a Users And Admins And Moderators And Posts ",
     *      @OA\Contact(
     *          email="abdulrahman.masoud.official@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="This Api For User Project"
     * )
     * @OA\PathItem(
     *   path="api/user",
     * )
     *
     * @OA\Tag(
     *     name="User",
     *     description="API Endpoints of User Project"
     * )
     
 
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
