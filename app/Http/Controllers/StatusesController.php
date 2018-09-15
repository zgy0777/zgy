<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;


class StatusesController extends Controller
{
    //设置中间件，使用此类方法时，用户必须登录才能进行以下操作
    //TODO:'Auth'相当于查找SESSION中是否有该AUTH保存的用户实例信息无则跳转到登录？
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request){

        //验证数据
        $this->validate($request,[
           'content'=>'required|max:140',
        ]);

        //AUTH::USER相当于取出登录用户的实例数据集
        //调用statuses关联创建
        Auth::user()->statuses()->create([
            'content'=>$request['content']
        ]);

        return redirect()->back();

    }

    public function destroy(Status $status){
        //授权删除指令
        $this->authorize('destroy',$status);
        //调用模型实例的delete()方法
        $status->delete();
        //session闪存，仅显示一次信息
        session()->flash('success','删除微博成功');
        //重定向到之前页面
        return redirect()->back();


    }





}
