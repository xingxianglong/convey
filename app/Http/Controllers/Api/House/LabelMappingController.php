<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabelMappingController extends BaseController
{
    public $table_name = 'web_house_label_mapping';
    public $table_pk = 'mapping_id';

    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    /**
     * 根据数组标签获取房源
     * @param array $label_id 标签id
     */
    public function AccordingArrayLabelGetHouse($label_id)
    {
        $where = array(
            [$this->table_name.'.is_delete','=',0],
        );
        $data = DB::table($this->table_name)
            ->distinct()
            ->select($this->table_name.'.house_id')
            ->where($where)
            ->whereIn($this->table_name.'.label_id',$label_id)
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$data);
    }


    /**
     * 根据房源获取数据
     * @param int $house_id 房源id
     * @param int $limit 显示多少条
     * @return array
     */
    public function AccordingHouseGetData($house_id,$limit=20)
    {
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.house_id','=',$house_id],
        );
        $data = DB::table($this->table_name)
            ->select('web_house_label.label_id','web_house_label.label_name')
            ->leftJoin('web_house_label',$this->table_name.'.label_id','=','web_house_label.label_id')
            ->where($where)
            ->limit($limit)
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$data);
    }


    /**
     * 根据房源更新标签
     * @param int $house_id 房源id
     * @param array $label_id 标签id
     * @param int $user_id 用户id
     */
    public function AccordingHouseUpdateLabel($house_id,$label_id,$user_id)
    {
        // 启动事务
        Db::beginTransaction();
        try {
            //先删除所有数据
            $delete_ps = '用户id：'.$user_id.' 手动删除';

            $update_data = array(
                'is_delete' => 1,
                'delete_time' => now(),
                'delete_ps' => $delete_ps,
                'delete_administrator_id' => $user_id,
            );
            $where = array(
                ['house_id','=',$house_id]
            );
            Db::table($this->table_name)
                ->where($where)
                ->update($update_data);

            foreach($label_id as $k => $v)
            {
                $where = array(
                    ['house_id','=',$house_id],
                    ['label_id','=',$v],
                );
                $info = DB::table($this->table_name)
                    ->select($this->table_pk.' as id')
                    ->where($where)
                    ->first();
                //更新
                if(!empty($info))
                {
                    $update_data = array(
                        'is_delete' => 0,
                        'delete_time' => null,
                        'delete_ps' => '',
                        'delete_administrator_id' => 0,
                        'update_time' => now(),
                        'update_administrator_id' => $user_id,
                    );
                    $where = array(
                        [$this->table_pk,'=',$info->id]
                    );
                    DB::table($this->table_name)
                        ->where($where)
                        ->update($update_data);
                }
                //新增
                else
                {
                    $insert_data = array(
                        'house_id' => $house_id,
                        'label_id' => $v,
                        'create_time' => now(),
                        'create_administrator_id' => $user_id,
                        'update_time' => now(),
                        'update_administrator_id' => $user_id,
                    );
                    DB::table($this->table_name)
                        ->insert($insert_data);
                }
            }

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->common_class->ajaxDataReturnFormat(1,'根据房源映射标签失败:'.$e->getMessage());
        }
        // 提交事务
        Db::commit();

        return $this->common_class->ajaxDataReturnFormat(0,'根据房源映射标签成功');
    }
}
