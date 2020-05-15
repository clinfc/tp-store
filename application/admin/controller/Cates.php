<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
use think\Request;

class Cates extends Base
{
    // 商品分类列表
    public function index()
    {
        $list = Db::table('product_cates') -> select();
        foreach ($list as $k => $v ) {
            $id_arr[$k] = $v['id'];
            $list[$k]['py_name'] = '';
        }
        $py_names = Db::table('property_name')->where('cid','in',$id_arr)->order('id','asc')->select();
        foreach ($py_names as $k => $v) {
            $py_name[$v['cid']][] = &$py_names[$k]['title'];
        }
        $this -> assign('list', $list);
        $this -> assign('py_name', $py_name);
        $this -> assign('count', Db::table('product_cates')->count());
        return $this -> fetch();
    }

    public function save(Request $rq)
    {
        $data = $rq -> except('status');
        $data['status'] = $rq->param('status') ? 1 : 0;
        $rule = [
            'ord|排序' => 'require|integer',
            'title|标签名称' => 'require'
        ];
        $verify = $this -> validate($data, $rule);
        if($verify === true) {
            if (isset($data['id'])) {
                Db::table('product_cates') -> update($data);
                $mg = '修改成功！';
            } else {
                $res = Db::table('product_cates') -> insert($data);
                $mg = $res ? '添加成功！' : '添加失败！';
            }
            return ['sta' => 1, 'mg' => $mg];
        } else {
            return ['sta'=>0, 'mg'=>$verify];
        }
        
    }

    // 更新 status 字段
    public function set_switch(Request $rq)
    {
        $id = $rq->param('num');
        $field = $rq->param('field');
        $info = Db::table('product_cates')->field($field)->find($id);
        // 如果该字段的值为1，则赋值为0；反之赋值为1
        $info[$field] = $info[$field] ? 0 : 1;
        Db::table('product_cates')->data($info)->where('id', $id)->update();
        return ['sta' => 1];
    }

    // 规格添加
    public function add_py_name()
    {
        $cid = (int)input('get.num');
        $cate = Db::table('product_cates') -> field('id,title') -> find($cid);
        $py_names = Db::table('property_name') -> where('cid', $cid) -> order('id', 'asc') -> select();

        $this -> assign('py_name', $py_names);
        $this -> assign('cate', $cate);
        return $this -> fetch();
    }

    // 保存规格
    public function py_name_save(Request $rq)
    {
        $cid = (int)$rq -> param('cid');
        if(!$cid) {
            return ['sta'=>0, 'mg'=>'无效CID！'];
        }
        $titles = $rq -> param('title/a');
        $cates = Db::table('property_name') -> where('cid', $cid) -> order('id','asc') -> select();
        if($cates) {
            foreach ($cates as $v) {
                $cate[$v['id']] = $v['title'];
                !in_array($v['title'], $titles) && $cate_del[] = $v['id'];
            }
            foreach ($titles as $v) {
                !in_array($v, $cate) && $cate_in[] = ['cid' => $cid, 'title' => $v];
            }
        } else {
            foreach ($titles as $v ) {
                $cate_in[] = ['cid' => $cid, 'title' => $v];
            }
        }
        // 删除已作废的规格
        isset($cate_del) && Db::table('property_name')->delete($cate_del);
        // 添加新增的规格
        isset($cate_in) && Db::table('property_name')->insertAll($cate_in);
        return ['sta'=>1, 'mg'=>implode('、',$titles)];
    }
}