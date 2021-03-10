<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\AppController;
use App\Http\Controllers\Common\CommonController;

class BaseController extends AppController
{

    public $administrator_info = null;
    public $common_class = null;


    /**
     * 初始化
     */
    public function __construct()
    {
        $this->common_class = new CommonController();

        if(empty($this->administrator_info)){
            if(isset($_COOKIE['administrator_info'])){
                try {
                    $this->administrator_info = decrypt($_COOKIE['administrator_info']);
                } catch (\Exception $e) {

                }
            }
        }
    }

}
