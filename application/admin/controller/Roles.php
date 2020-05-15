<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
use think\Request;

class Roles extends Base
{
    /* 角色-列表 */
    public function index()
    {
        $group = Db::table('admin_groups')->order('gid')->select();
        foreach ($group as $k => $v) {
            $v && $v['rights'] && $group[$k]['rights'] = json_decode($v['rights']);
        }
        $menus = Db::table('admin_menus')->order('pid,mid')->select();
        $this -> assign('group', $group);
        $this -> assign('menus', $this->sorts($menus));
        return $this -> fetch();
    }

    public function sorts($data)
    {
        $list = [];
        foreach ($data as $k => $v) {
            // if($v['pid']) {
            //     $list[$v['pid']]['childs'][] = $v;
            // } else {
            //     $list[$v['mid']] = $v;
            // }
            isset($v) && $v['pid'] ? $list[$v['pid']]['childs'][] = $v : $list[$v['mid']] = $v;
        }
        return $list;
    }

    /* 保存角色信息 */
    public function save(Request $rq)
    {
        $gid = $rq -> param('gid');
        $title = $rq->param('title');
        $rights = $rq -> param('menu/a');
        $right = [];
        foreach ($rights as $k => $v) {
            $right[] = $k;
        }
        $rights = json_encode($right);
        $verify = $this -> validate(['title'=>$title], ['title|角色名称'=>'require|chsAlpha']);
        if($verify === true) {
            $gid ? $data = ['gid'=>$gid, 'title'=>$title, 'rights'=>$rights] : $data = ['title' => $title, 'rights' => $rights];
            $gid ? $res = Db::table('admin_groups') -> data($data) -> update()
                : $res = Db::table('admin_groups') -> data($data) -> insert();
            return ['sta'=>1, 'mg'=>'保存成功！'];
        } else {
            return ['sta'=>0, 'mg'=>$verify];
        }
    }

    /* 删除角色 */
    public function del()
    {
        $gid = $this -> request -> param('gid');
        $res = Db::table('admin_groups') -> delete($gid);
        return ['sta'=>$res];
    }
}