<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
use think\Request;

class Slide extends Base
{
    // 首页首屏列表
    public function index()
    {
        $slide = Db::table('slide') -> order('id', 'desc') -> paginate(15);
        $this -> assign('slide', $slide);
        $this -> assign('count', Db::table('slide') -> count());
        return $this -> fetch();
    }

    // 首屏添加
    public function add(Request $rq)
    {
        // 如果 type 值为 to 则加载首屏添加页面
        if($rq -> param('type') == 'to' ) {
            return $this->fetch();
        }

        // 否则，保存数据
        $data = $rq->except('type');
        $rule = [
            'title|标题' => 'require', 'ord|排序' => 'require|integer', 'url|URL' => 'require|url'
        ];
        $verify = $this->validate($data, $rule);
        if ($verify === true) {
            $id = Db::table('slide')->insertGetId($data);
            return ['sta' => 1, 'mg' => $id];
        } else {
            return ['sta' => 0, 'mg' => $verify];
        }
    }

    // 首屏编辑
    public function edit(Request $rq)
    {
        // 如果 type 值为 to 则加载首屏添加页面
        if($rq->param('type') == 'to') {
            $id = $rq -> param('num');
            $slide = Db::table('slide') -> find($id);
            $this -> assign('slide', $slide);
            return $this -> fetch();
        }

        // 否则，保存数据
        $data = $rq->except('type');
        $rule = [
            'title|标题' => 'require', 'ord|排序' => 'require|integer', 'url|URL' => 'require|url'
        ];
        $verify = $this->validate($data, $rule);
        if ($verify === true) {
            Db::table('slide')->update($data);
            return ['sta' => 1, 'mg' => ''];
        } else {
            return ['sta' => 0, 'mg' => $verify];
        }
    }

    // 首屏删除
    public function delete()
    {
        $id = (int)input('get.num');
        if($id) {
            Db::table('slide') -> delete($id);
            return ['sta'=>1, 'mg'=>''];
        }
        return ['sta'=>0, 'mg'=>'无效ID，操作失败！'];
    }
}