<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use think\Db;

class Base extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!Session::has('name')) {
            // header('location:/index.php/admin/account/login');
            $this -> redirect('/index.php/admin/account/login');
            exit();
        }
        $this->gid = Session::get('gid');
        $group = Db::table('admin_groups')->where(['gid'=>1])->find();
        $this->group = $group;
        if(!$group) {
            $this -> rq_error('很抱歉，非管理员，无法访问！');
        }
        $this->right = (isset($group['rights']) && $group['rights']) ? json_decode($group['rights']) : [];
        
        // 获取当前访问的控制器名称
        $con = request() -> controller();
        // 获取当前访问的方法名
        $act = request() -> action();

        $res = Db::table('admin_menus') -> where(['controller'=>$con, 'method'=>$act]) -> find();
        if(!$res) {
            $this->rq_error('很抱歉，您访问的功能不存在');
        }
        if($res['status'] == 0) {
            $this->rq_error('很抱歉，该功能已被下架');
        }
        if(!in_array($res['mid'], $this->right)) {
            $this->rq_error('很抱歉，您没有该功能的访问权限');
        }
    }

    private function rq_error($mg) {
        if(request()->isAjax()) {
            exit(json_encode(['sta'=>0, 'mg'=>$mg]));
        }
        exit($mg);
    }
}