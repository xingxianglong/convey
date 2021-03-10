<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\AppController;

class CommonController extends Controller
{

    // 应用公共文件

    /**
     * 截取中文字符，截取长度是否大于等于原字符串，若否，则在输出字符串末尾加上...
     *
     *	@param string $str 需要截断的字符串
     *
     *	@param string $start 截断开始处，起始处为0
     *
     *	@param string $length 要截取的字数
     *
     *	@param string $encoding 网页编码，如utf-8,GB2312,GBK
     *
     *  @return string $res
     */
    function mb_substrex($str, $start, $length, $encoding, $pad_str='...')
    {
        $str_len=mb_strlen($str, $encoding);
        $res=mb_substr($str, $start, $length, $encoding);
        if($str_len>$length)
        {
            $res.=$pad_str;
        }
        return $res;
    }


    /**
     * 使用curl进行post方式传输数据
     *
     * @param string $post_url 访问的目标页面url
     *
     * @param array $post_data post传输的参数 array(key => value)
     *
     * @param array $header 请求头
     *
     * @param bool $ssl_verifypeer 是否进行SSL验证
     *
     * @param int $timeout 请求超时时间
     *
     * @return string $result 返回结果数据
     *
     */
    function curl_post($post_url, $post_data, $header=array(), $ssl_verifypeer = false,$timeout = 0)
    {
        // 初始化
        $curl = curl_init();

        // 设置访问页面url
        curl_setopt($curl, CURLOPT_URL, $post_url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        // 是否检查SSL证书:否
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
        // 是否验证SSL主机(域名):否
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ssl_verifypeer);

        // 设置使用post方式传输数据
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // post数据
        curl_setopt($curl, CURLOPT_POST, 1);

        // post的变量
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        if($timeout >= 1)
        {
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        }
        // 执行并获取返回数据
        $result = curl_exec($curl);

        // 释放curl句柄
        curl_close($curl);

        // 打印获得的数据
        // print_r($result);

        return $result;
    }


    /**
     * 返回当前日期时间
     *
     * @return string
     */
    function now(){
        return date('Y-m-d H:i:s');
    }


    /**
     * 返回当前毫秒
     *
     * @return string
     */
    function millisecond(){
        list($microsecond , $time) = explode(' ', microtime()); //' '中间是一个空格
        $timestamp =  (float)sprintf('%.0f',(floatval($microsecond)+floatval($time))*1000);
        return $timestamp;
    }


    /**
     * 密码格式验证
     *
     * @param string $string 字符串内容
     *
     * @return string $res
     */
    function passwordFormatCheck($string) {
        $check = '/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/';
        if(!preg_match($check, $string))
        {
            return $this->ajaxDataReturnFormat(1,'密码必须包含数字和字母');
        }
        elseif(strlen($string) < 6)
        {
            return $this->ajaxDataReturnFormat(1,'密码长度不能小于6位');
        }
        elseif(strlen($string) > 32)
        {
            return $this->ajaxDataReturnFormat(1,'密码长度不能大于32位');
        }


        return $this->ajaxDataReturnFormat(0,'密码格式验证成功');
    }


    /**
     * 传递数字，返回星期几/周几
     *
     * @param int $number 数字
     *
     * @param int $type 类型，1-返回星期，2-返回周
     *
     * @return string
     */
    function week($number,$type){
        $week = '';
        if($type == 1){
            $week .= '星期';
        }elseif($type == 2){
            $week .= '周';
        }
        if($number == 1){
            $week .= '一';
        }elseif($number == 2){
            $week .= '二';
        }elseif($number == 3){
            $week .= '三';
        }elseif($number == 4){
            $week .= '四';
        }elseif($number == 5){
            $week .= '五';
        }elseif($number == 6){
            $week .= '六';
        }elseif($number == 7){
            $week .= '日';
        }

        return $week;
    }


    /**
     * ajax接口数据返回格式
     *
     * @param int $code 状态码
     *
     * @param string $msg 提示
     *
     * @param array $data 数据
     *
     * @param int $count 数据总数
     *
     * @param int $page_count 页码总数
     *
     * @return array
     */
    function ajaxDataReturnFormat($code,$msg='',$data=array(),$count=null,$page_count=null)
    {
        $res = array(
            'code' => $code, //状态码
            'msg' => $msg, //提示
            'data' => $data ? $data : array(), //数据
        );

        if(!is_null($count)){
            $res['count'] = $count; //数据总数
        }
        if(!is_null($page_count)){
            $res['page_count'] = $page_count; //页码总数
        }

        return $res;
    }


    /**
     * 获取两个日期月份相差数
     *
     * @param string $date1 开始日期
     *
     * @param string $date2 结束日期
     *
     * @param string $tags 符号
     *
     * @return array
     */
    function getMonthNum($date1,$date2,$tags='-'){
        $date1 = explode($tags,$date1);
        $date2 = explode($tags,$date2);

        $number = 0;
        $tep_year = 0; //临时变量用来判断是否跨年，垮了几年。
        if($date1[1] > $date2[1])
        {
            $tep_year = abs($date1[0] - $date2[0]);
            $number = abs($date1[0] - $date2[0]) * 12 - abs($date1[1] - $date2[1]);
        }
        else
        {
            $tep_year = abs($date1[0] - $date2[0]);
            $number = abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
        }
        if($date1[2] == '01' || $date1[2] == 1)
        {
            $tep_date1 = $date1[0].'-'.$date1[1].'-'.$date1[2];
            $tep_date2 = $date2[0].'-'.$date2[1].'-'.$date2[2];

            $tep_date1_time = '';
            $tep_date2_time = strtotime($tep_date2) + 86400;
            if($tep_year > 0)
            {
                $tep_date1_time = strtotime(date('Y-m-d',strtotime('+'.$tep_year.' year',strtotime($tep_date1))));
            }

            if($tep_date1_time == $tep_date2_time)
            {
                $number ++;
            }

        }

        return $number;
    }


    /**
     * 检验日期格式
     *
     * @param int $date 日期
     *
     * @return array
     */
    function checkDataFormat($date){
        //匹配日期格式
        if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
        {
            //检测是否为日期
            if(checkdate($parts[2],$parts[3],$parts[1]))
            {
                return ajaxDataReturnFormat(0,'日期格式正确',$parts);
            }

            else
            {
                return ajaxDataReturnFormat(1,'日期格式错误');
            }
        }
        else
        {
            return ajaxDataReturnFormat(1,'日期格式错误');
        }
    }


    /**
     * 验证手机号码
     *
     * @param string $phone 手机号码
     *
     * @return string $res
     */
    function checkPhone($phone) {
        $check = '/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/';
        if (preg_match($check, $phone)) {
            return true;
        }

        return false;
    }


    /**
     * 验证字符串中是否存在这个值
     *
     * @param string $value 值
     *
     * @param string $string 字符串
     *
     * @return string $res
     */
    function checkValueExistString($value,$string) {
        $array = explode(',',$string);
        if(in_array($value,$array))
        {
            return 1;
        }

        return 0;
    }


    /**
     * 邮箱格式验证
     *
     * @param string $phone 手机号码
     *
     * @return string $res
     */
    function checkEmail($email) {
        $check = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
        if (preg_match($check, $email)) {
            return true;
        }

        return false;
    }


    /**
     * base64加密
     *
     * @param string $string
     *
     * @return string $res
     */
    function base64Encryption($string)
    {
        $res = base64_encode('md5_'.$string.'_md5');

        return $res;
    }


    /**
     * base64解密
     *
     * @param string $string
     *
     * @return string $res
     */
    function base64Dncryption($string)
    {
        $subscript = strpos(base64_decode($string),'_md5');

        $res = substr(base64_decode($string),0,$subscript);

        $res = str_replace('md5_','',$res);

        return $res;
    }


    /**
     * 计算两组经纬度坐标之间的距离
     *
     * @param float $lat1 纬度1
     *
     * @param float $lng1 经度1
     *
     * @param float $lat2 纬度2
     *
     * @param float $lng2 经度2
     *
     * @param float $len_type 长度单位，1-米，2-千米
     *
     * @param float $decimal 小数点后保留几位
     *
     * return m or km
     */
    function calculateLongitudeLatitudeDistance($lat1,$lng1,$lat2,$lng2,$len_type=1,$decimal=2){
        $EARTH_RADIUS = 6371.393; //地球半径
        $PI = 3.1415926; //地球半径

        $radLat1 = $lat1 * $PI / 180.0;
        $radLat2 = $lat2 * $PI / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $s = $s * $EARTH_RADIUS;
        $s = round($s * 1000);
        if ($len_type == 2)
        {
            $s /= 1000;
        }

        return round($s, $decimal);
    }


    /**
     * 解析XML
     */
    function xmlToArr($xml)
    {
        libxml_disable_entity_loader(true);
        $xml_string = simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);

        $result = json_decode(json_encode($xml_string),true);
        return $result;
    }


    /**
     * 微信退款
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    function wxRefundPostXmlCurl( $url,$xml, $useCert = false, $second = 30,$flg=0)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($useCert){
            $SSLCERT_PATH = EXTEND.'/WxApi/cert/apiclient_cert.pem';
            $SSLKEY_PATH = EXTEND.'/WxApi/cert/apiclient_key.pem';
            curl_setopt($ch, CURLOPT_SSLCERT, $SSLCERT_PATH);
            curl_setopt($ch, CURLOPT_SSLKEY, $SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);

        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            echo '错误码:'.$error;
        }
    }


    /**
     * 数字转人民币大写
     *
     * @param float $num 数字
     *
     * @return string
     */
    function digitalConversionRmbCapital($num){
        $rmbNum = ['零','壹','贰','叁','肆','伍','陆','柒','捌','玖'];
        $rmbUnit = ['分','角','元','拾','佰','仟','万','拾','佰','仟','亿','拾','佰','仟','万','拾','佰','仟'];
        //数字预处理
        if(!isset($num) || empty($num))
        {
            return ajaxDataReturnFormat(1,'请传入需要转换的数字');
        }
        elseif(!is_numeric($num))
        {
            return ajaxDataReturnFormat(1,'请传入数字类型');
        }
        else
        {
            $num = number_format($num, 2, '.', '') * 100;
        }
        //开始数字转换人民币大写
        $out1 = 0;
        $numRmb = '';
        $unit = '';
        $rmb = '';
        for ($out1 = 0 ; $out1 < strlen(number_format($num, 0, '', '')) ; $out1++) {
            $numRmb = $rmbNum[substr(number_format($num, 0, '', ''), $out1, 1)];
            $unit = $rmbUnit[strlen(number_format($num, 0, '', '')) - $out1 - 1];
            $rmb = $rmb.$numRmb.$unit;
        }
        // 将大写人民币中的零进行调整
        $rmbStrlen = strlen($rmb);
        for ($out2=0; $out2 < $rmbStrlen; $out2=$out2+3) {
            $tempRmb = substr($rmb, $out2, 6);
            if ($tempRmb=='零元'||$tempRmb=='零万'||$tempRmb=='零亿') {
                $left = substr($rmb, 0, $out2);
                $right = substr($rmb, $out2+3);
                $rmb = $left.$right;
                $out2 = $out2 - 3;
                $rmbStrlen = $rmbStrlen - 3;
            }elseif ($tempRmb=='零拾'||$tempRmb=='零佰'||$tempRmb=='零仟') {
                $left = substr($rmb, 0, $out2+3);
                $right = substr($rmb, $out2+6);
                $rmb = $left.$right;
                $out2 = $out2 - 3;
                $rmbStrlen = $rmbStrlen - 3;
            }elseif ($tempRmb=='零分'||$tempRmb=='零角') {
                $left = substr($rmb, 0, $out2);
                $right = substr($rmb, $out2+6);
                $rmb = $left.$right;
                $out2 = $out2 - 6;
                $rmbStrlen = $rmbStrlen - 6;
            }
            if ($tempRmb=='零零') {
                $left = substr($rmb, 0, $out2);
                $right = substr($rmb, $out2+3);
                $rmb = $left.$right;
                $out2 = $out2 - 3;
                $rmbStrlen = $rmbStrlen - 3;
            }
            if(substr($rmb,strlen($rmb)-3)=='元') {
                $rmb = $rmb.'整';
            }
        }

        return ajaxDataReturnFormat(0,'转换成功',$rmb);
    }


    /**
     * 数字转大写数字
     *
     * @param float $num 数字
     *
     * @return string
     */
    function lowercaseDigitalConversionCapitalDigital($num)
    {
        $capital_digital = array('零','一','二','三','四','五','六','七','八','九','十');

        if((!isset($num) || empty($num)) && $num != 0)
        {
            return ajaxDataReturnFormat(1,'请传入需要转换的数字');
        }
        elseif(!is_numeric($num))
        {
            return ajaxDataReturnFormat(1,'请传入数字类型');
        }

        $str_len = strlen($num);


        $res = '';
        for($i=0;$i<$str_len;$i++)
        {
            $tep_num = substr($num,$i,1);
            if($tep_num == 0)
            {
                $res .= $capital_digital[0];
            }
            elseif($tep_num == 1)
            {
                $res .= $capital_digital[1];
            }
            elseif($tep_num == 2)
            {
                $res .= $capital_digital[2];
            }
            elseif($tep_num == 3)
            {
                $res .= $capital_digital[3];
            }
            elseif($tep_num == 4)
            {
                $res .= $capital_digital[4];
            }
            elseif($tep_num == 5)
            {
                $res .= $capital_digital[5];
            }
            elseif($tep_num == 6)
            {
                $res .= $capital_digital[6];
            }
            elseif($tep_num == 7)
            {
                $res .= $capital_digital[7];
            }
            elseif($tep_num == 8)
            {
                $res .= $capital_digital[8];
            }
            elseif($tep_num == 9)
            {
                $res .= $capital_digital[9];
            }
            elseif($tep_num == 10)
            {
                $res .= $capital_digital[10];
            }
        }

        return ajaxDataReturnFormat(0,'转换成功',$res);
    }


    /**
     * 计算分页
     *
     * @param int $limit 每页数量
     *
     * @param int $count 总数量
     *
     * @return string
     */
    function calculatePaging($limit,$count)
    {
        if($limit == 0)
        {
            $page_count = 1;
        }else{
            if ($count % $limit == 0) {
                $page_count = $count / $limit;
            } else {
                $page_count = (int) ($count / $limit) + 1;
            }
        }

        return $page_count;
    }


    /**
     * 地址解析(地址转坐标)
     *
     * @param string $province_name 省份名称
     *
     * @param string $city_name 城市名称
     *
     * @param string $district_name 区域名称
     *
     * @param string $detail_address 详细地址
     *
     * @return array
     */
    function AddressParsing($province_name,$city_name,$district_name,$detail_address)
    {
        $app_class = new AppController();

        $key = $app_class->TENCNETPOSITIONKEY;

        $address = $province_name.$city_name.$district_name.$detail_address;

        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?address='.$address.'&key='.$key;

        // 创建一个新cURL资源
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        // 是否检查SSL证书:否
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // 是否验证SSL主机(域名):否
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 抓取URL并把它传递给浏览器
        $result = curl_exec($curl);

        // 关闭cURL资源，并且释放系统资源
        curl_close($curl);

        return $result;
    }

}
