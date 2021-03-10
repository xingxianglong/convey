<?php

namespace App\Http\Controllers\Admin\House;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabelMappingController extends BaseController
{
    public $table_name = 'web_house_label_mapping';
    public $table_pk = 'mapping_id';


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 根据房源更新标签
     * @param int $house_id 房源id
     * @param array $label_id 标签id
     */
    public function AccordingHouseUpdateLabel($house_id,$label_id)
    {
        // 启动事务
        Db::beginTransaction();
        try {
            //先删除所有数据
            $delete_ps = '管理员id：'.$this->administrator_info['administrator_id'].' 手动删除';

            $update_data = array(
                'is_delete' => 1,
                'delete_time' => now(),
                'delete_ps' => $delete_ps,
                'delete_administrator_id' => $this->administrator_info['administrator_id'],
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
                        'update_administrator_id' => $this->administrator_info['administrator_id'],
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
                        'create_administrator_id' => $this->administrator_info['administrator_id'],
                        'update_time' => now(),
                        'update_administrator_id' => $this->administrator_info['administrator_id'],
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


    /**
     * 根据房源获取数据
     * @param int $house_id 房源id
     * @return array
     */
    public function AccordingHouseGetData($house_id)
    {
        $where = array(
            [$this->table_name.'.is_delete','=',0],
            [$this->table_name.'.house_id','=',$house_id],
        );
        $data = DB::table($this->table_name)
            ->select($this->table_pk.' as id','label_id')
            ->where($where)
            ->get();

        return $this->common_class->ajaxDataReturnFormat(0,'获取成功',$data);
    }
}
