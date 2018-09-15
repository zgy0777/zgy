<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//调用微博模型相关的命名空间
use App\Http\Controllers\Controller;    //这个是便于引入AUTH授权？？？本身不是继承了吗
use App\Models\Status;                  //引入微博模型（数据表）
use Illuminate\Support\Facades\Auth;    //引入Auth门脸，必须登录过才显示
class StaticPagesController extends Controller
{
    //主页
    public function home(){
        //定义空数组
        $feed_items=[];
        //TODO:用户登录成功后，才将信息保存到该数组中，否则返回空数组
        if(Auth::check()){
            $feed_items=Auth::user()->feed()->paginate(30);
        }
        //绑定参数回显到视图
        return view('static_pages/home',compact('feed_items'));
    }

    //帮助页
    public function help(){
        return view('static_pages/help');
    }

    //关于页
    public function about(){
        return view('static_pages/about');
    }

}
