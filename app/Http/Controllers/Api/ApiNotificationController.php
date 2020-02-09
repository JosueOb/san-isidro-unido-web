<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiBaseController;
use App\Helpers\Utils;
// Exception

class ApiNotificationController extends ApiBaseController
{
    //
    public  $baseUrl;

    public function __construct()
    {
        $utils = new Utils();
        $this->baseUrl = $utils->getAppURL() . "/api/v1";
    }

 
}
