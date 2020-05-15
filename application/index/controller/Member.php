<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\facade\Request;
use think\Db;

class Member extends Base
{
    // 商品加入购物车
    public function add_carts()
    {
        $this->is_login();
        $data = Request::param();
        $pt_sku = Db::table('product_sku')->field('id,product_id,stock')->where('id',$data['sku_id'])->find();
        if(!$pt_sku) {
            return ['sta'=>0, 'mg'=>'该商品不存在！'];
        } 
        if(!$pt_sku['stock']) {
            return ['sta'=>0, 'mg'=>'该商品库存不足！'];
        }
        $pt = Db::table('product')->field('status')->where('id', $pt_sku['product_id'])->find();
        if($pt['status'] <= 0) {
            return ['sta'=>0, 'mg'=>'该商品已下架！'];
        }
        $isset = Db::table('cart')->where('product_id',$pt_sku['product_id'])->find();
        if($isset) {
            return ['sta'=>0, 'mg'=>'该商品已存在于您的购物车，无需重复添加'];
        }
        $data = array_merge($data, [
            'uid' => $this -> user['uid'],
            'add_time' => time(),
            'product_id' => $pt_sku['product_id']
        ]);
        $id = Db::table('cart')->insertGetId($data);
        return ['sta'=>1, 'mg'=>$id];
    }

    // 购物车
    public function carts()
    {
        $carts = Db::table('cart')->where('uid', $this->user['uid'])->select();
        $pt_id = array_column($carts, 'product_id');
        $sku_id = array_column($carts, 'sku_id');
        if(!$pt_id || !$sku_id) {
            return $this->fetch();
        }
        foreach ($carts as $k => $v) {
            $cart[$v['product_id']] = &$carts[$k];
        }
        $pts = Db::table('product')-> field('id,cid,pro_no,desc,img') -> where('id','in',$pt_id)->select();
        foreach ($pts as $k => $v) {
            $pts[$k]['title'] = mb_strlen($v['desc'], 'utf8') > 40 ? mb_substr($v['desc'],0,45).'......' : $pts[$k]['desc'];
            $cart[$v['id']]['pt'] = &$pts[$k];
        }
        $pt_skus = Db::table('product_sku')->where('id','in',$sku_id)->select();
        foreach ($pt_skus as $k => $v) {
            $cart[$v['product_id']]['price'] = &$pt_skus[$k]['price'];
            $cart[$v['product_id']]['stock'] = &$pt_skus[$k]['stock'];
            foreach (explode(';', $v['properties']) as $pv ) {
                $kv = explode(':', $pv);
                $cart[$v['product_id']]['spec'][$kv[0]] = $kv[1];
            }
        }
        $this->assign('cart', $cart);

        // 查询商品对应的规格名称
        $cid = array_column($pts,'cid');
        $py_names = Db::table('property_name')->where('cid','in',$cid)->select();
        foreach ($py_names as $k => $v) {
            $py_name[$v['id']] = &$py_names[$k]['title'];
        }
        $this->assign('name', $py_name);

        // 查询商品对应的规格属性
        $py_vals = Db::table('property_value')->where('product_id','in',$pt_id)->select();
        foreach ($py_vals as $k => $v) {
            $py_val[$v['id']] = &$py_vals[$k]['value'];
        }
        $this->assign('val', $py_val);
        
        $this->assign('count', count($carts));
        return $this->fetch();
    }
   
}
