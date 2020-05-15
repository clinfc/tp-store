<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
use think\Request;

class Menu extends Base
{
    private $rule = [
        'title|栏目名' => 'require|chsDash',
        'controller' => 'require|alpha',
        'method' => 'require|alphaDash',
        'pid' => 'require|number'
    ];


    // 菜单管理
    public function index()
    {
        $list = Db::table('admin_menus') -> order('pid,mid') -> select();
        $this -> assign('count', Db::table('admin_menus')->count());
        $this -> assign('list', $this->sorts($list));
        return $this -> fetch();
    }



    public function sorts($data)
    {
        $list = [];
        foreach ($data as $k => $v) {
            if($v['pid'] == 0) {
                $list[$v['mid']][] = &$data[$k];
            } else {
                $list[$v['pid']][] = &$data[$k];
            }
        }
        return $list;
    }

    // 获取菜单添加页面
    public function add(Request $rq)
    {
        $data = $rq -> param();
        $this -> assign('data',$data);
        return $this -> fetch();
    }


    // 菜单保存
    public function save(Request $rq)
    {
        $data = $rq -> param();
        $data['ishidden'] = isset($data['ishidden']) ?  1 : 0;
        $data['status'] = isset($data['status']) ? 1 : 0;
        // 当添加的栏目为二级栏目时
        if($data['pid']) {
            $rule = $this -> rule;
            $where = $rq -> only('title,controller,method');
            $mg = '栏目名、controller、method已存在！';
        } else {
            $rule = ['title|栏目名' => 'require|chsDash'];
            $where = $rq -> only('title');
            $mg = '该栏目名已存在！不可重复添加！';
        }
        $verify = $this->validate($data, $rule);
        if ($verify === true) {
            $table = 'admin_menus';
            $has = Db::table($table) -> where($where) -> find();
            if($has === null) {
                $in = Db::table($table)->insert($data);
                while ($in) {
                    return ['sta'=>1];
                }
                return ['sta'=>0, 'mg'=>$in];
            } else {
                return ['sta'=>0, 'mg'=>$mg];
            }
        } else {
            return ['sta' => 0, 'mg' => $verify];
        }
    }


    // 菜单编辑
    public function edit(Request $rq)
    {
        // 存在参数less时，加载表单，否则保存数据
        if($rq->param('less')) {
            $id = $rq->param('id');
            $info = Db::table('admin_menus')->find(['mid'=>$id]);
            $this -> assign('info', $info);
            return $this -> fetch();
        } else {
            $data = $rq -> param();
            $data['ishidden'] = isset($data['ishidden']) ? 1 : 0;
            $data['status'] = isset($data['status']) ? 1 : 0;
            if($data['pid']) {
                $rule = $this -> rule;
            } else {
                $rule = ['title|栏目名' => 'require|chsDash'];
            }
            $verify = $this -> validate($data,$rule);
            if($verify === true) {
                Db::table('admin_menus')->data($data)->update();
                return ['sta'=>1];
            } else {
                return ['sta'=>0, 'mg'=>$verify];
            }
        }
    }

    // 更新 ishidden 或 status 字段
    public function set_switch(Request $rq)
    {
        $mid = $rq -> param('num');
        $field = $rq -> param('field');
        $info = Db::table('admin_menus') -> field($field) -> find($mid);
        // 如果该字段的值为1，则赋值为0；反之赋值为1
        $info[$field] = $info[$field] ? 0 : 1;
        Db::table('admin_menus') -> data($info) -> where('mid', $mid) -> update();
        return ['sta'=>1];
    }

    // 菜单删除
    // public function del(Request $rq)
    // {
    //     $id = $rq -> id;
    //     $less = $rq -> less;
    //     // 判断是否为首次数据访问
    //     if($less=='to') {
    //         $count = Db::table('admin_menus')->where('pid',$id)->count();
    //         // 判断删除的菜单是否有子菜单
    //         // 如果该菜单有子菜单，返回 0，询问是否继续删除
    //         if($count) 
    //             return ['sta'=>0]; 
    //         // 如果该菜单无子菜单，直接更新数据表
    //         else {
    //             Db::table('admin_menus')->where('mid',$id)->update(['isdel'=>1]);
    //             return ['sta' => 1];
    //         }
    //     } elseif($less=='do') {
    //         Db::table('admin_menus')->where('mid', $id)->whereOr('pid',$id)->update(['isdel' => 1]);
    //     }
    // }
    // 菜单删除
    public function del(Request $rq)
    {
        $id = $rq -> id;
        $less = $rq -> less;
        // 判断是否为首次数据访问
        if($less=='to') {
            $count = Db::table('admin_menus')->where('pid',$id)->count();
            // 判断删除的菜单是否有子菜单
            // 如果该菜单有子菜单，返回 2，询问是否继续删除
            if($count) 
                return ['sta'=>2]; 
            // 如果该菜单无子菜单，直接更新数据表
            else {
                // Db::table('admin_menus')->where('mid',$id)->update(['isdel'=>1]);
                Db::table('admin_menus')->where('mid',$id)->delete();
                return ['sta' => 1];
            }
        } elseif($less=='do') {
            // Db::table('admin_menus')->where('mid', $id)->whereOr('pid',$id)->update(['isdel' => 1]);
            Db::table('admin_menus')->where('mid', $id)->whereOr('pid',$id)->delete();
        }
    }

}