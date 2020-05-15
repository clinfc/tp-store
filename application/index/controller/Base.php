<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Session;

class Base extends Controller
{
    // 判断用户是否登录
    public function is_login()
    {
        !Session::has('user') && $this -> redirect('/index.php/index/account/login');
    }

    public function __construct()
    {
        parent::__construct();
        $this->user = Session::get('user');
        $this->assign('user', $this->user);
    }
}