<?php
namespace app\admin\controller;

use think\captcha\Captcha;
use think\Controller;
use think\Request;
use think\Db;
use think\facade\Session;

class Account extends Controller
{
    // 自定义验证码
    public function verify()
    {
        $conf = [
            'useCurve' => false,    // 是否画混淆曲线
            'useNoise' => true,     // 是否添加杂点
            'imageH' => 50,         // 验证码图片高度，设置为0为自动计算
            'imageW' => 150,        // 验证码图片宽度，设置为0为自动计算
            'length' => 4,          // 验证码位数
            'fontSize' => 20,       // 验证码字体大小(px)
            'reset' => true,        // 验证成功后是否重置
        ];
        $captcha = new Captcha($conf);
        return $captcha -> entry();
    }

    // 判断是否已存在登录用户
    public function login()
    {
        // 如果当前不存在登录用户，加载登录页面
        if(!Session::has('name')){
            return $this -> fetch();
        }
        // 否则，跳转到后台主界面
        $this -> redirect('/index.php/admin/home/index');

        // return $this->fetch();

    }

    // 验证用户登录
    public function doLogin(Request $rq)
    {
        $sta = 0;
        $mg = '';
        $data = $rq -> param();
        $rule = [
            'name|用户名' => 'require|chsAlphaNum',
            'pass|密码' => 'require|alphaNum',
            'captcha|验证码' => 'require|captcha'
        ];
        $verify = $this -> validate($data, $rule);
        if($verify === true) {
            $res = Db::table('admins')  -> where('name', $data['name']) -> find();
            if($res === null) {
                $mg = '该用户名不存在！';
            } elseif($res['pass'] != $data['pass']) {
                $mg = '您输入的密码有误！';
            } elseif(!$res['status']) {
                $mg = '该用户已被禁用，请联系管理员！';
            } else {
                $sta = 1;
                Db::table('admins') -> where('id', $res['id']) -> setInc('count_login');
                Session::set('name', $res['name']);
                Session::set('gid', $res['gid']);
                Session::set('true', $res['truename']);
            }
        } else {
            $mg = $verify;
        }
        return ['sta'=>$sta, 'mg'=>$mg];
    }

    // 退出登录
    public function logout()
    {
        Session::delete('name');
        Session::delete('gid');
        Session::delete('true');
        return '您已安全退出！';
    }
}