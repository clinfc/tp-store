<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
use think\Request;
use think\File;

class Product extends Base
{
    // 商品列表
    public function index()
    {
        $list = Db::table('product') -> order('id', 'desc') -> paginate(10);
        $cates = Db::table('product_cates') -> field('id,title') -> where('status', 1) -> select();
        foreach ($cates as $v ) {
            $cate[$v['id']] = $v['title'];
        }
        $this -> assign('list', $list);
        $this -> assign('cates', $cate);
        $this -> assign('count', Db::table('product')->count());
        return $this -> fetch();
    }

    // 商品添加
    public function add()
    {
        $cates = Db::table('product_cates') -> where('status', 1) -> select();
        $this -> assign('cates', $cates);
        return $this -> fetch();
    }

    // 图片上传
    public function up_img(Request $rq)
    {
        $file = $rq -> file('file');
        $info = $file -> validate(['ext'=>'jpg,jpeg,png,gif']) -> move('../public/uploade/');
        if ($info) {
            $img = str_replace('\\','/',$info->getSaveName());
            return ['sta'=>1, 'mg'=> $img];
        } else {
            return ['sta'=>0, 'mg'=>$info->getError()];
        }
    }

    // 添加规格
    public function get_sku_view(Request $rq)
    {
        $cid = (int)($rq -> param('cid'));
        if(!$cid) {
            return ['sta'=>0, 'mg'=>'请选择您的商品类目'];
        }
        // $data['list'] = Db::table('product_name') -> where('cid', $cid) -> select();
        $data = Db::table('property_name') -> where('cid', $cid) -> order('id', 'asc') -> select();
        $this -> assign('data', $data);
        $view = $this -> fetch();
        return ['mg'=>$view, 'sta'=>1];
        // return json_encode(['mg' => $view, 'sta' => 1]);
    }

    // 表单数据保存
    public function save(Request $rq)
    {
        // （product 数据表）
        // 保存信息到“product”
        $other = $rq -> except('table,img');
        $other['add_time'] = time();
        $other['pro_no'] = rand(10000, 99999).time().rand(10000, 99999);
        $p_id = Db::table('product') -> insertGetId($other);

        // （product_img 数据表）
        // 保存图片
        $img_str = $rq->param('img');
        if($img_str) {
            $img_a = explode(';', ltrim($img_str, ';'));
            foreach ($img_a as $v) {
                $img[] = [
                    'product_id' => $p_id,
                    'img' => $v,
                    'add_time' => time()
                ];
            }
            Db::table('product_img') -> insertAll($img);
        }


        // 保存规格参数
        $propertys = [];
        $table = $rq -> param('table');

        // （property 数据表）
        // 提取规格属性
        foreach($table as $v) {
            $fixed = $v['fixed'];
            foreach ($fixed as $v) {
                $item = ['name_id'=>$v['id'], 'value'=>$v['val'], 'product_id'=>$p_id];
                // 判断该项规格属性值是否已存在（是否重复），不存在则添加
                if(!in_array($item,$propertys)) {
                    $propertys[] = $item;
                }
            }
        }
        
        // 保存规格属性值
        foreach ($propertys as $v ) {
            // 查询该数据是否存在
            $item = Db::table('property_value') -> where($v) -> find();
            // 如果不存在，插入数据并返回自增id；否则，获取该数据的id
            if(!$item) {
                $value_id = Db::table('property_value') -> insertGetId($v);
            } else {
                $value_id = $item['id'];
            }
            // 组装规格属性（property数据表）数据
            $property_list[] = ['product_id'=>$p_id, 'name_id'=>$v['name_id'], 'value_id' => $value_id];
            $val_ids[$value_id] = ['id'=>$v['name_id'], 'val'=>$v['value']]; 
        }
        // 保存规格属性（property数据表）键值对
        Db::table('property') -> insertAll($property_list);
        
        // （property_sku 数据表）
        // 获取数据
        foreach ($table as $v ) {
            $fixed = $v['fixed'];
            $editable = $v['editable'];
            $sku_info = ['product_id'=>$p_id, 'price'=>$editable['price'], 'cost'=>$editable['cost'], 'stock'=>$editable['stock']];
            // 组装 “property_sku.properties” 字段数据
            foreach ($fixed as $v ) {
                $value_id = array_search($v, $val_ids);
                if($value_id) {
                    $kv_a[$v['id']] = $v['id'].':'.$value_id; 
                }
            }
            // 升序排列
            ksort($kv_a);
            $sku_info['properties'] = implode(';', $kv_a);

            $sku_list[] = $sku_info;
        }
        // 保存数据
        Db::table('product_sku') -> insertAll($sku_list);

        // 获取最低价格
        $update = Db::table('product_sku') -> field('price, cost') -> where('product_id', $p_id) -> order('price', 'asc') -> find();
        // 获取主图
        $img = Db::table('product_img') -> field('img') -> where('product_id', $p_id) -> order('id', 'asc') -> find();
        $update['img'] = $img['img'];
        // 更新 （product 数据表）
        Db::table('product') -> where('id', $p_id) -> update($update);
        return ['sta'=>1,'mg'=>''];
    }

    // 商品编辑
    public function edit()
    {
        // 基础数据

        $p_id = (int)input('get.num');
        $pt = Db::table('product')->field('id,cid,title,keywords,desc,status')->where('id', $p_id)->find();
        // 获取该商品图片
        $img = Db::table('product_img')->field('img')->where('product_id', $p_id)->select();
        foreach ($img as $k => $v) {
            $img_a[] = &$img[$k]['img'];
        }
        $pt['img'] = &$img_a;
        // 将该商品图片名称组装为字符串
        $pt['img_str'] = isset($img_a) ? implode(';', $img_a) : '';
        // 获取商品类目
        $pt_cates = Db::table('product_cates')->where('status', 1)->select();
        $this->assign('pt', $pt);
        $this->assign('cates', $pt_cates);

        // 商品规格

        $cid = $pt['cid'];
        $py_name = Db::table('property_name')->where('cid', $cid)->select();
        $py = Db::table('property')->where('product_id', $p_id)->select();
        // 组装：获取对应的“property_value”表的id值
        foreach ($py as $v) {
            $py_v_id[] = $v['value_id'];
        }
        $py_val = Db::table('property_value')->select($py_v_id);
        // 数据重装：形成“['id'=>'value']”的键值对数组
        foreach ($py_val as $k => $v) {
            $py_val_info[$v['id']] = &$py_val[$k]['value'];
        }
        // 数据重装：形成“['name_id' => ['value', 'value']]”的键值对数组
        foreach ($py as $v) {
            $py_info[$v['name_id']][] = &$py_val_info[$v['value_id']];
            $name_ids[$v['name_id']] = 't' . $v['name_id'];
        }


        $this->assign('py_name', $py_name);
        $this->assign('py_info', $py_info);

        return $this->fetch();
    }

    // 商品编辑：规格属性列表
    public function get_sku_table()
    {
        $p_id = (int)input('post.id');
        $cid = (int)input('post.cid');

        // 组装 thead 数据
        
        // 获取该商品的规格属性名称的ID
        $py_name_ids = Db::table('property')->where('product_id', $p_id)->group('name_id')->select();
        // 重装规格属性名称的ID，形成 ['name_id','name_id',...] 形式的数据
        foreach ($py_name_ids as $v) {
            $py_name_id[] = $v['name_id'];
        }
        // 获取该商品拥有的规格属性名称
        $py_name = Db::table('property_name')->where('cid', $cid)->select();
        // 数据重装，形成 ['id'=>'title'] 形式的数字
        foreach ($py_name as $k => $v) {
            $py_n_a[$v['id']] = &$py_name[$k]['title'];
        }
        // 完善 thead 数据
        foreach ($py_name_id as $v) {
            $pt_sku_thead[] = ['field' => "t{$v}", 'title' => $py_n_a[$v]];
        }
        // 组装 thead 完整数据
        $pt_sku_thead = array_merge($pt_sku_thead, [
            ['field' => 'price', 'title' => '价格', 'templet' => '#price'],
            ['field' => 'cost', 'title' => '成本', 'templet' => '#cost'],
            ['field' => 'stock', 'title' => '库存', 'templet' => '#stock']
        ]);

        // 组装 tbody 数据
        $pt_sku_tbody = [];

        // 获取该商品规格属性名称与属性值的对应关系
        $py = Db::table('property')->where('product_id', $p_id)->select();
        // 数据重组：形成 ['value_id','value_id',...] 形式的数组，以便于在 “property_value” 数据表中查找对应的数据
        foreach ($py as $v) {
            $py_val_id[] = $v['value_id'];
        }
        // 在数据表 “property_value” 中获取该商品的规格属性值数据
        $py_vals = Db::table('property_value')->field('id,value')->where('id', 'in', $py_val_id)->select();
        // 数据重组，形成 ['id'=>'value',...] 形式的数组
        foreach ($py_vals as $k => $v) {
            $py_val[$v['id']] = $py_vals[$k]['value'];
        }
        // 获取该商品的规格属性数据列表
        $pt_sku = Db::table('product_sku')->where('product_id', $p_id)->select();
        // 数据组装：形成 ['id'=>["price"=>"value", "cost"=>"value",...] ] 形式的数据
        // while ($pt_sku) {
            foreach ($pt_sku as $k => $v) {
                $pt_sku_tbody[$v['id']]['price'] = &$pt_sku[$k]['price'];
                $pt_sku_tbody[$v['id']]['cost'] = &$pt_sku[$k]['cost'];
                $pt_sku_tbody[$v['id']]['stock'] = &$pt_sku[$k]['stock'];
                $pt_ps[$v['id']] = explode(';', $v['properties']);
            }
            foreach ($pt_ps as $ps_k => $ps_v) {
                foreach ($ps_v as $k => $v) {
                    $sta = explode(':', $v);
                    $pt_sku_tbody[$ps_k]['t' . $sta[0]] = $py_val[$sta[1]];
                }
            }
        // }
        

        // 返回 thead 和 tbody 数据
        return ['thead' => $pt_sku_thead, 'tbody' => $pt_sku_tbody];
    }

    // 商品编辑：表单数据保存
    public function save_edit(Request $rq)
    {
        // （product 数据表）
        // 保存信息到“product”
        $p_id = $rq -> param('id');
        // $other = $rq -> except('id,table,img');
        // Db::table('product') -> data($other) -> where('id', $p_id) -> update();

        // // （product_img 数据表）
        // // 保存图片
        $img_str = $rq->param('img');
        if($img_str) {
            // 查询原始数据
            $pt_imgs = Db::table('product_img') -> field('id,img') -> where('product_id', $p_id) -> select();
            // 将原始数据整理成 ['id'=>'img',...] 形式的数组
            foreach ($pt_imgs as $k => $v ) {
                $pt_img[$v['id']] = &$pt_imgs[$k]['img'];
            }
            $img_a = explode(';', ltrim($img_str, ';'));
            // 如果该图片为新增图片，则将该图片添加到数据库
            foreach ($img_a as $v) {
                !in_array($v, $pt_img) && $img[] = ['product_id' => $p_id, 'img' => $v, 'add_time' => time()];
            }
            isset($img) && Db::table('product_img') -> insertAll($img);
        }


        // 保存规格参数
        $propertys = [];
        $table = $rq -> param('table');

        // （property 数据表）
        // 提取规格属性，形成 [['name_id'=>'id', 'value'=>'value'],...] 的二维数组
        foreach($table as $v) {
            $fixed = $v['fixed'];
            foreach ($fixed as $v) {
                $item = ['name_id'=>$v['id'], 'value'=>$v['val'], 'product_id'=>$p_id];
                // 判断该项规格属性值是否已存在（是否重复），不存在则添加
                if(!in_array($item,$propertys)) {
                    $propertys[] = $item;
                }
            }
        }
        // 查询 property_value 表原始的规格属性值
        $py_vals = Db::table('property_value') -> where('product_id', $p_id) -> select();
        // 整理原始数据，形成 ['id'=>['name_id'=>'', 'value_id'=>],...] 形式的数组
        foreach ($py_vals as $v ) {
            // $py_val[$v['id']] = ['name_id'=> $v['name_id'], 'value'=> $v['value']];
            $item = ['name_id'=> $v['name_id'], 'value'=> $v['value'], 'product_id' => $p_id];
            $py_val[$v['id']] = $item;
            !in_array($item, $propertys) && $py_val_del[] = $v['id'];
        }
        // 删除被剔除的项
        isset($py_val_del) && Db::table('property_value') -> delete($py_val_del);
        // 判断用于更新的数据是否为原始数据中不存在的数据
        foreach ($propertys as $v ) {
            $value_id = in_array($v, $py_val) ? array_search($v, $py_val) : Db::table('property_value')->insertGetId($v);
            $property[] = ['product_id' => $p_id, 'name_id' => $v['name_id'], 'value_id' => $value_id];
            $val_ids[$value_id] = ['id' => $v['name_id'], 'val' => $v['value']];
        }

        // 查询property数据表相关数据
        $pys = Db::table('property')->where('product_id', $p_id)->select();
        if ($pys) {
            foreach ($pys as $v) {
                $py[$v['id']] = $item = ['name_id' => $v['name_id'], 'value_id' => $v['value_id'], 'product_id' => $v['product_id']];
                !in_array($item, $property) && $py_del[] = $v['id'];
            }
            isset($py_del) && Db::table('property')->delete($py_del);
            foreach ($property as $k => $v) {
                !in_array($v, $py) && $in[] = &$property[$k];
            }
            isset($in) && Db::table('property')->insertAll($in);
        } else {
            Db::table('property')->insertAll($property);
        }
        
        // （property_sku 数据表）
        // 删除原始数据
        Db::table('product_sku') -> where('product_id', $p_id) -> delete();
        foreach ($table as $v) {
            $fixed = $v['fixed'];
            $editable = $v['editable'];
            $sku_info = ['product_id' => $p_id, 'price' => $editable['price'], 'cost' => $editable['cost'], 'stock' => $editable['stock']];
            // 组装 “property_sku.properties” 字段数据
            foreach ($fixed as $v) {
                $value_id = array_search($v, $val_ids);
                if ($value_id) {
                    $kv_a[$v['id']] = $v['id'] . ':' . $value_id;
                }
            }
            // 升序排列
            ksort($kv_a);
            $sku_info['properties'] = implode(';', $kv_a);

            $sku_list[] = $sku_info;
        }
        // 保存数据
        Db::table('product_sku')->insertAll($sku_list);

        // 获取最低价格
        $update = Db::table('product_sku') -> field('price, cost') -> where('product_id', $p_id) -> order('price', 'asc') -> find();
        // 获取主图
        $img = Db::table('product_img') -> field('img') -> where('product_id', $p_id) -> find();
        $update['img'] = $img['img'];
        // 更新 （product 数据表）
        Db::table('product') -> where('id', $p_id) -> update($update);
        return ['sta'=>1, 'mg'=>''];
    }

    // 商品删除
    public function delete()
    {
        $p_id = (int)input('get.num');
        Db::table('product') -> where('id', $p_id) -> update(['status'=>'-1']);
        return ['sta'=>1, 'mg'=>'该商品已删除'];
    }
}