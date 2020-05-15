<?php
namespace app\admin\controller;

use app\admin\controller\Base;

class Index extends Base
{
    // 字符串翻到
    public function index()
    {
        $str = 'laojiaoke';
        $arr = str_split($str, 1);
        // for($i = count($arr) - 1; $i >= 0; $i-- ){
        //     $reverse_order_arr[] = $arr[$i];
        // }
        // $new_str = implode('', $reverse_order_arr);
        $reverse_order_str = '';
        for($i = count($arr) - 1; $i >= 0; $i-- ){
            $reverse_order_str .= $arr[$i];
        }
        echo $reverse_order_str;
    }

    // 截取一级域名
    public function get_domain_name($domain)
    {
        // 判断传入的字符是否为域名
        $judge = !empty($domain) 
            && strpos($domain, '--') === false 
            && preg_match('/^([a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?\.)?[a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?(\.us|\.tv|\.org\.cn|\.org|\.net\.cn|\.net|\.mobi|\.me|\.la|\.info|\.hk|\.gov\.cn|\.edu|\.com\.cn|\.com|\.co\.jp|\.co|\.cn|\.cc|\.biz)$/i', $domain) 
                ? true : false;
        // 如果传入的字符为正确的域名，截取一级域名
        if($judge) {
            $arr = explode('.', $domain);
            $first_domain = $arr[ count($arr) - 2 ] . '.' . $arr[count($arr) - 1];
            return ['sta'=>1, 'mg'=>$first_domain];
        } else {
            return ['sta'=>0, 'mg'=>'传入的域名有误！'];
        }
    }

    // 正则替换
    public function replace()
    {
        mb_regex_encoding('utf-8');
        $str = '重庆市市辖区合川区xx路xx号；重庆市县铜梁县xx路yy号；四川省广元市元坝区西洋路122号';
        $re_str = mb_ereg_replace('\市市辖区|\市县', '市', $str );
        echo $re_str;
    }

    /** 
     * (3) x = 2, y = 5, z = 7;
     *  mx + ny + kz = 25,
     *  m, n, k均为正整数，
     *  请用php计算满足该方程 的 m, n, k （多组解） 
     */
    public function samsara()
    {
        $x = 2;
        $y = 5;
        $z = 7;
        for($m = 0; $m < 13; $m++ ) {
            for($n = 0; $n < 5; $n++ ) {
                for($k = 0; $k < 4; $k++ ) {
                    $m * $x + $n * $y + $k * $z == 25 && $assemble[] = ['m'=>$m, 'n'=>$n, 'k'=>$k];
                }
            }
        }
        dump($assemble);
    }
}