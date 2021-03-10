<?php

namespace App\Http\Controllers\Api\Advice;

use App\Http\Controllers\Api\BaseController;
use App\Rules\Api\Advice\Advice as RulesAdvice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdviceController extends BaseController
{
    public $table_name = 'web_advice';
    public $table_pk = 'advice_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 新增预约
     * @param Request $request
     * @param RulesAdvice $validator
     */
    public function Add(Request $request,RulesAdvice $validator)
    {
        $validator_res = $validator->Add($request->all());
        if(!empty($validator_res))
        {
            return $this->common_class->ajaxDataReturnFormat(1,$validator_res);
        }

        $all = $request->all();

        $phone = $all['phone']; //手机号码
        $content = $all['content']; //内容
        $img = isset($all['img']) ? $all['img'] : ''; //图片

        if($this->common_class->checkPhone($phone) == false)
        {
            return $this->common_class->ajaxDataReturnFormat(1,'手机格式错误');
        }

        // 启动事务
        Db::beginTransaction();
        try {
            $insert_data = array(
                'user_id' => $request->user_info->user_id,
                'phone' => $phone,
                'content' => $content,
                'img' => $img,
                'create_time' => now(),
                'create_user_id' => $request->user_info->user_id,
                'update_time' => now(),
                'update_user_id' => $request->user_info->user_id,
            );
            DB::table($this->table_name)
                ->insert($insert_data);

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'提交失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'提交成功');
    }

}
