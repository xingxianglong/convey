<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public $XCX_APPID = 'wx4f67f6448ecc88d5'; //小程序APPid
    public $XCX_APPSECRET = 'c39ff0bb7faebabf93ae9d47c15064ca'; //AppSecret(小程序密钥)

    public $MCHID = ''; //商户号
    public $SECRETKEY = ''; //商户号密钥


    public $TENCNETPOSITIONKEY = 'BDBBZ-6ZC6F-FFMJU-JUHFD-MLXYJ-BVFKQ'; //腾讯位置KEY

}
