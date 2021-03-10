<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Common\AppController;
use App\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;

class BaseController extends AppController
{

    public $common_class = null;


    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        $this->common_class = new CommonController();
    }

}
