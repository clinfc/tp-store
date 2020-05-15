<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
use think\facade\Session;

class Home extends Base
{
    public function index()
    {
        if($this->right) {
            $menus = Db::table('admin_menus') 
                -> where('mid', 'in', implode(',', $this->right))
                -> where('ishidden',0)
                -> where('status', 1) 
                -> select();
            $menus && $menus = $this->assortment($menus);
        }
        
        $this -> assign('menus', $menus);
        $this -> assign('group', $this->group);
        $this -> assign('name', Session::get('name'));
        return $this -> fetch();
    }

    public function welcome()
    {
        $time = date('Y.m.d H:i');
        $this -> assign('time', $time);
        $this -> assign('name', Session::get('name'));
        $this -> assign('mod', php_sapi_name());
        return $this -> fetch();
    }

    private function assortment($info)
    {
        $tree = [];
        foreach ($info as $k => $v) {
            // 方法一
            // if(!$v['pid']) {
            //     $tree[$v['mid']] = $info[$k];
            // } else {
            //     $tree[$v['pid']]['children'][] = $info[$k];
            // }
            !$v['pid'] ?  $tree[$v['mid']] = $info[$k] : $tree[$v['pid']]['children'][] = $info[$k];
            // 方法二
            // if(!$v['pid']) {
            //     $tree[] = &$info[$k];
            // } else {
            //     foreach($info as $ke => $va){
            //         if($v['pid'] == $va['mid']){
            //             $info[$ke]['children'][] = &$info[$k];
            //         }
            //     }
            // }
        }
        return $tree;
    }
}