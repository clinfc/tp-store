<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
use think\Request;
use think\Validate;

class Admins extends Base
{
    public function adminList(Request $rq)
    {
        // submit按钮，检测是否为正规数据查询
        $btn = $rq -> btn;
        // 搜索类型
        $cat = $rq -> category;
        // 用户名 或 真实姓名 数据
        $sel = $rq -> name;
        // 角色
        $gid = $rq -> gid;
        // 状态
        $sta = $rq -> status;

        // echo(implode('%', str_split($sel)));

        // 查询管理员角色并整理成数组 $group = ['gid'=>'title', ...]
        $group;
        $primitive_group = Db::table('admin_groups') -> field('gid, title') -> select();
        foreach ($primitive_group as $v) {
            $group[$v['gid']] = $v['title'];
        }

        // 查询指定用户
        if(isset($btn) && $btn == 'sel') {

            switch ($cat) {
                case 'name':
                    $list = Db::table('admins') -> where("name", "like", "%{$sel}%") -> select(); 
                    break;
                case 'tname':
                    
                    $list = Db::table('admins') -> where("truename", "like", "%{$sel}%") -> select();
                    break;
                case 'gid':
                    $list = Db::table('admins') -> where('gid', $gid) -> select();
                    break;
                case 'status':
                    $list = Db::table('admins') -> where('status', $sta) -> select();
                    break;
            }

        // 查询所有用户
        } else {

            $list = Db::table('admins') -> select();

        }

        foreach($list as $k => $v) {
            $list[$k]['group'] = $group[$v['gid']];
            if($v['status']==1) {
                $list[$k]['setsta'] = '禁用';
            } else {
                $list[$k]['setsta'] = '启用';
            }
        }

        $this -> assign('list', $list);
        $this -> assign('group', $group);
        return $this -> fetch();
    }

    public function setStatus(Request $rq)
    {
        $id = $rq -> id;
        $res = Db::table('admins') -> find($id);
        if($res['status']==1) {
            $reset = 0;
        } else {
            $reset = 1;
        }
        $res = Db::table('admins')->where('id', $id)->update(['status' => $reset]);
    }

    public function adminEdit(Request $rq)
    {
        $id = $rq -> param('id');
        $info = Db::table('admins') -> where('id',$id) -> find();
        $group = Db::table('admin_groups') -> field('gid,title') -> select();
        $this -> assign('info', $info);
        $this -> assign('group', $group);
        return $this -> fetch();
    }

    public function doEdit(Request $rq)
    {
        $pass = $rq -> param('pass');
        if(isset($pass) && $pass) {
            $rule = ['pass|密码'=> 'require|length:2,12|alphaDash'];
            $data = $rq -> param();
            $verify = $this -> validate($data, $rule);
            if($verify !== true) {
                return ['sta'=>0,'mg'=>$verify];
            }
        } else {
            $data = $rq -> except('pass');
        }
        $res = Db::table('admins') -> update($data);
        return ['sta'=>1,'mg'=>''];
    }

    public function adminAdd()
    {
        $group = Db::table('admin_groups') -> field('gid,title') -> select();
        $this -> assign('group', $group);
        return $this -> fetch();
    }

    public function doAdd(Request $rq)
    {
        $data = $rq -> param();
        $rule = [
            'name|用户名' => 'require|min:2|alphaNum',
            'truename|真实用户名' => 'require|min:2|chsAlphaNum',
            'pass|密码' => 'require|length:6,12|alphaNum'
        ];
        $verify = $this -> validate($data, $rule);
        if($verify !== true)  
            return ['sta'=>0, 'mg'=>$verify];
        $into = Db::table('admins') -> data($data) -> insert();
        if($into == 1) 
            return ['sta'=>1, 'mg'=>''];
        else 
            return ['sta'=>0, 'mg'=>$into];
    }

    public function select(Request $rq)
    {
        $name = $rq -> param('val');
        $res = Db::table('admins') -> where('name',$name) -> find();
        $res !== null ? $list[] = $res : $list = [] ;
        foreach (str_split($name) as $k => $v) {
            $res = Db::table('admins') -> where('name', 'like', "%{$v}%") -> find();
            if($res !== null) 
                $list[] = $res;
        }
        if($list) {
            $group = Db::table('admin_groups')->select();
            foreach ($list as $k => $v) {
                foreach ($group as $kg => $vg) {
                    if ($v['gid'] == $vg['gid'])
                        $list[$k]['group'] = $vg['title'];
                }
                if ($v['status'] == 1) {
                    $list[$k]['setsta'] = '禁用';
                } else {
                    $list[$k]['setsta'] = '启用';

                }
            }
            $this->assign('list', $list);
            $this->assign('count', Db::table('admins')->count());
            return $this->fetch('admin_list');
        } else {
            return ['sta'=>0];
        }
    }
}