<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\captcha\Captcha;
use think\Request;
use think\Db;
use think\facade\Session;

class Account extends Base
{
    // 自定义验证码
    public function verify()
    {
        $conf = [
            'useCurve' => false,
            'useNoise' => true,
            'imageH' => 38,
            // 'imageW' => 150,
            'length' => 4,
            'fontSize' => 20,
            'reset' => true,
        ];
        $captcha = new Captcha($conf);
        return $captcha->entry();
    }


    // 弹出式登录页
    public function login()
    {
        return $this -> fetch();
    }

    // 全网页式登录页
    public function logins()
    {
        return $this -> fetch();
    }

    // 用户注册
    public function registers()
    {
        $set = [
            'welcome' => '欢迎注册',
            'repass' => ' ',
            'title' => '用户注册',
            'show' => 'regist',
            'to' => '立即登录'
        ];
        $this->assign('set', $set);
        return $this->fetch('logins');
    }

    // 用户登录
    public function do_login(Request $rq)
    {
        $data = $rq -> param();
        $rule = [
            'username|用户名' => 'require',
            'password|密码' => 'require',
            'captcha|验证码' => 'require|captcha'
        ];
        $verify = $this -> validate($data, $rule);
        if($verify !== true) {
            return ['sta'=>0, 'mg'=>$verify];
        }
        $info = Db::table('user') -> where('username', $data['username']) -> find();
        if(!$info) {
            return ['sta'=>0, 'mg'=>'该用户名不存在！'];
        }
        if($data['password'] != $info['password']) {
            return ['sta'=>0, 'mg'=>'您的密码有误！'];
        }
        Session::set('user.uid',$info['uid']);
        Session::set('user.name',$info['username']);
        return ['sta'=>1, 'mg'=>'登录成功！'];
    }

    // 用户注册
    public function do_regist(Request $rq)
    {
        $data = $rq -> param();
        $rule = [
            'username|用户名' => 'require',
            'password|密码' => 'require',
            'captcha|验证码' => 'require|captcha'
        ];
        $verify = $this -> validate($data, $rule);
        if($verify !== true) {
            return ['sta'=>0, 'mg'=>$verify];
        }
        unset($data['captcha']);
        $data['add_time'] = time();
        $uid = Db::table('user')->insertGetId($data);
        Session::set('user.uid', $uid);
        Session::set('user.name', $data['username']);
        return ['sta'=>1, 'mg'=>'注册成功'];
    }

    // 退出登录
    public function logout()
    {
        Session::clear();
        $this -> redirect('/index.php/index/index/index');
    }
}