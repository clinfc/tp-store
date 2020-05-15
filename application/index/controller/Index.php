<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\Db;
use think\Request;
use think\facade\Session;

class Index extends Base
{

    // public function hello($name = 'ThinkPHP5')
    // {
    //     return 'hello,' . $name;
    // }

    // 首页
    public function index()
    {
        // return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
        
        // 首页首屏
        $slides = Db::table('slide') -> order('id', 'desc') -> limit(7) -> select();
        // 热门推荐
        $recoms = Db::table('product') -> where('status',1) -> order('id', 'desc') -> limit(6) -> select();

        $this -> assign('slides', $slides);
        $this -> assign('recoms', $recoms);
        $this -> assign('cart_count', Db::table('cart')->count());
        
        return $this -> fetch();
    }

    // 商品详情页
    public function details()
    {
        $num = input('get.num');

        // 基本信息查询
        $pt = Db::table('product') -> field('id,cid,pro_no,desc,img,price') -> where('pro_no', $num) -> find();
        // 如果该商品不存在，跳转到首页
        !$pt && $this->redirect('/index.php/index/index/index');
        // 更新点击量
        Db::table('product') -> where('id', $pt['id']) -> setInc('pv');
        $this->assign('pt', $pt);

        // 图片查询
        $imgs = Db::table('product_img')->field('id,img')->where('product_id', $pt['id'])->select();
        $this->assign('img', $imgs);

        // 规格查询

        // 查询规格名称
        $py_names = Db::table('property_name') -> where('cid', $pt['cid']) -> select();
        // 数据重组，形成 ['id'=>'title', ...] 形式的新数组
        foreach ($py_names as $v ) {
            $py_name[$v['id']] = $v['title'];
        }
        // 查询规格名称对应的值
        $py_vals = Db::table('property_value') -> where('product_id', $pt['id']) -> select();
        // 数据重组，形成 ['id'=>'value', ...] 形式的新数组
        foreach ($py_vals as $v ) {
            $py_val[$v['id']] = $v['value'];
        }
        // 查询规格名称与规格属性值的对应关系数据
        $pys = Db::table('property') -> where('product_id', $pt['id']) -> select();
        // 数据重组
        foreach ($pys as $v ) {
            $py[$v['name_id']][] = $v['value_id'];
        }
        ksort($py);

        $this->assign('py_val', $py_val);
        $this->assign('py_name', $py_name);
        $this->assign('pys', $py);
        $this->assign('cart_count', Db::table('cart')->count());

        $user = Session::get('user');
        // $this->assign('user', $user);
        
        return $this->fetch();
    }

    public function get_price(Request $rq)
    {
        $icon = $rq -> param('icon');
        foreach ($icon as $v ) {
            $ps[$v['nam']] = $v['nam'].':'.$v['val'];
        }
        ksort($ps);
        $pt_sku = Db::table('product_sku') -> field('id,price,cost,stock') -> where('properties', implode(';', $ps)) -> find();
        return $pt_sku;
    }
}
