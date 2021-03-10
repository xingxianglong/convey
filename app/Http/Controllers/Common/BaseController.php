<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\CommonController;

class BaseController extends AppController
{

    public $common_class = null;


    /**
     * 初始化
     */
    public function __construct()
    {
        $this->common_class = new CommonController();

    }

}
