<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SessionsController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('guest',[
            //只允许未登录的用户进行以下操作
           'only'=>['create']
        ]);
    }

    public function create(){
        return view('sessions.create');
    }

    public function store(Request $request){
        //验证
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        //TODO:逻辑
        if(Auth::attempt($credentials,$request->has('remember'))){
            //TODO::判断登录用户实例，的activated是否为true
            if(Auth::user()->activated) {
                session()->flash('success', '欢迎回来！');
                return redirect()->intended(route('users.show', [Auth::user()]));
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }

        }else{
            session()->flash('danger','非常抱歉，您的邮箱或密码不匹配');
            return redirect()->back();
        }

    }

    public function destroy(){
        Auth::logout();
        session()->flash('success','您已成功退出');
        return redirect('login');
    }

}
