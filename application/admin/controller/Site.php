<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;


class Site extends Base
{
    public function index()
    {
        $site = Db::table('sites') -> where('name', 'site') -> order('id', 'desc') -> find();
        $site && $site['value'] = json_decode($site['value']);
        $this -> assign('site', $site);
        return $this -> fetch();
    }

    public function save()
    {
        $id = (int)input('post.id');
        $sta = 1;
        if($id) {
            $data = ['id'=>$id, 'name'=>input('post.name'), 'value'=>json_encode(input('post.value'))];
            Db::table('sites') -> update($data);
        } else {
            $data = ['name' => input('post.name'), 'value' => json_encode(input('post.value'))];
            $get_id = Db::table('sites')->insertGetId($data);
            !$get_id && $sta = 0;
        }
        return ['sta'=>$sta];
    }
}