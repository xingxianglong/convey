<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

class UploadController extends BaseController
{
    public $path = 'upload/';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 图片上传
     *
     * @param $request
     *
     * return array
     * */
    public function Img(Request $request){
        $all = $request->all();
        //获取参数
        $file_path = isset($all['file_path']) ? $all['file_path'] : 'upload_img';

        //验证上传文件
        if(is_array($_FILES['file']['name']))
        {
            foreach($_FILES['file']['name'] as $k => $v)
            {
                if(empty($v))
                {
                    return $this->common_class->ajaxDataReturnFormat(1,'上传文件参数值不能为空');
                }
            }
        }
        elseif(is_string($_FILES['file']['name']))
        {
            if(empty($_FILES['file']['name']))
            {
                return $this->common_class->ajaxDataReturnFormat(1,'上传文件参数值不能为空');
            }
        }
        else
        {
            return $this->common_class->ajaxDataReturnFormat(1,'上传文件类型错误');
        }

        // 获取表单上传文件 例如上传了001.jpg
        $file = $request->file('file');

        if(empty($file)){
            return $this->common_class->ajaxDataReturnFormat(1,'请上传文件');
        }

        $data = array();
        //多个
        if(is_array($file)){
//            foreach($file as $k => $v){
//                $info = $request->file('file')->store($this->path.$file_path);
//                if($info){
//                    $request = Request::instance();
//
//                    $data[$k]['src'] = env('APP_URL').'/'.$info; //完整路径
//                    $data[$k]['url'] = '/'.$info; //相对路径
//                    $data[$k]['ext'] = $file->extension(); //文件后缀
//                    $data[$k]['size'] = $file->getSize(); //文件大小
//
//                    $data[$k]['src'] = str_replace('\\','/',$data[$k]['src']);
//                    $data[$k]['url'] = str_replace('\\','/',$data[$k]['url']);
//                }
//                else
//                    {
//                    // 上传失败获取错误信息
//                    return $this->common_class->ajaxDataReturnFormat(1,$v->getError());
//                }
//            }
        }
        //单个
        else{
            if($file){
                $info = $request->file('file')->store($this->path.$file_path);
                if($info){
                    $data['src'] = env('APP_URL').'/'.$info; //完整路径
                    $data['url'] = '/'.$info; //相对路径
                    $data['ext'] = $file->extension(); //文件后缀
                    $data['size'] = $file->getSize(); //文件大小

                    $data['src'] = str_replace('\\','/',$data['src']);
                    $data['url'] = str_replace('\\','/',$data['url']);
                }
                else
                    {
                    // 上传失败获取错误信息
                    return $this->common_class->ajaxDataReturnFormat(1,$file->getError());
                }
            }
        }

        return $this->common_class->ajaxDataReturnFormat(0,'上传成功',$data);
    }


    /**
     * 视频上传
     *
     * */
    public function Video(){
        //获取参数
        $file_path = input('file_path','upload_video');

        //验证上传文件
        if(is_array($_FILES['file']['name']))
        {
            foreach($_FILES['file']['name'] as $k => $v)
            {
                if(empty($v))
                {
                    return json(ajaxDataReturnFormat(1,'上传文件参数值不能为空'));
                }
            }
        }
        elseif(is_string($_FILES['file']['name']))
        {
            if(empty($_FILES['file']['name']))
            {
                return json(ajaxDataReturnFormat(1,'上传文件参数值不能为空'));
            }
        }
        else
        {
            return json(ajaxDataReturnFormat(1,'上传文件类型错误'));
        }

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');

        if(empty($file)){
            return json(ajaxDataReturnFormat(1,'请上传文件'));
        }

        $data = array();
        //多个
        if(is_array($file)){
            foreach($file as $k => $v){
                $url = 'upload'.'/'.$file_path;
                $info = $v->validate(['ext'=>'AVI,rmvb,wma,flash,mp4'])->move($url);
                if($info){
                    $request = Request::instance();

                    $data[$k]['src'] = $request->domain().'/'.$url.'/'.$info->getSaveName(); //完整路径
                    $data[$k]['url'] = '/'.$url.'/'.$info->getSaveName(); //相对路径
                    $data[$k]['ext'] = $info->getExtension(); //文件后缀
                    $data[$k]['size'] = $info->getInfo()['size']; //文件大小
                }else{
                    // 上传失败获取错误信息
                    return json(ajaxDataReturnFormat(1,$v->getError()));
                }
            }
        }
        //单个
        else{
            if($file){
                $url = 'upload/'.$file_path;
                $info = $file->validate(['ext'=>'AVI,rmvb,wma,flash,mp4'])->move($url);
                if($info){
                    $request = Request::instance();

                    $data['src'] = $request->domain().'/'.$url.'/'.$info->getSaveName(); //完整路径
                    $data['url'] = '/'.$url.'/'.$info->getSaveName(); //相对路径
                    $data['ext'] = $info->getExtension(); //文件后缀
                    $data['size'] = $info->getInfo()['size']; //文件大小
                }else{
                    // 上传失败获取错误信息
                    return json(ajaxDataReturnFormat(1,$file->getError()));
                }
            }
        }

        return json(ajaxDataReturnFormat(0,'上传成功',$data));
    }


    /**
     * Excel上传
     *
     * */
    public function Excel(){
        //获取参数
        $file_path = input('file_path','upload_excel');

        //验证上传文件
        if(is_array($_FILES['file']['name']))
        {
            foreach($_FILES['file']['name'] as $k => $v)
            {
                if(empty($v))
                {
                    return json(ajaxDataReturnFormat(1,'上传文件参数值不能为空'));
                }
            }
        }
        elseif(is_string($_FILES['file']['name']))
        {
            if(empty($_FILES['file']['name']))
            {
                return json(ajaxDataReturnFormat(1,'上传文件参数值不能为空'));
            }
        }
        else
        {
            return json(ajaxDataReturnFormat(1,'上传文件类型错误'));
        }

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');

        if(empty($file)){
            return json(ajaxDataReturnFormat(1,'请上传文件'));
        }

        $data = array();
        //多个
        if(is_array($file)){
            foreach($file as $k => $v){
                $url = 'upload'.'/'.$file_path;
                $info = $v->validate(['ext'=>'xls,xlsx'])->move($url);
                if($info){
                    $request = Request::instance();

                    $data[$k]['src'] = $request->domain().'/'.$url.'/'.$info->getSaveName(); //完整路径
                    $data[$k]['url'] = '/'.$url.'/'.$info->getSaveName(); //相对路径
                    $data[$k]['ext'] = $info->getExtension(); //文件后缀

                    $data[$k]['src'] = str_replace('\\','/',$data[$k]['src']);
                    $data[$k]['url'] = str_replace('\\','/',$data[$k]['url']);
                    $data[$k]['size'] = $info->getInfo()['size']; //文件大小
                }else{
                    // 上传失败获取错误信息
                    return json(ajaxDataReturnFormat(1,$v->getError()));
                }
            }
        }
        //单个
        else{
            if($file){
                $url = 'upload/'.$file_path;
                $info = $file->validate(['ext'=>'xls,xlsx'])->move($url);
                if($info){
                    $request = Request::instance();

                    $data['src'] = $request->domain().'/'.$url.'/'.$info->getSaveName(); //完整路径
                    $data['url'] = '/'.$url.'/'.$info->getSaveName(); //相对路径
                    $data['ext'] = $info->getExtension(); //文件后缀

                    $data['src'] = str_replace('\\','/',$data['src']);
                    $data['url'] = str_replace('\\','/',$data['url']);
                    $data['size'] = $info->getInfo()['size']; //文件大小

                }else{
                    // 上传失败获取错误信息
                    return json(ajaxDataReturnFormat(1,$file->getError()));
                }
            }
        }

        return json(ajaxDataReturnFormat(0,'上传成功',$data));
    }
}
