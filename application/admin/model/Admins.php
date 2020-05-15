<?php
namespace app\admin\model;

use think\Model;

class Admins extends Model
{
    public function getStatusAttr($val)
    {
        $sta = [
            0 => '禁用',
            1 => '正常'
        ];
        return $sta[$val];
    }

    public function getCreateTimeAttr($val)
    {
        return date('Y-m-d H:i:s', $val);
    }
}