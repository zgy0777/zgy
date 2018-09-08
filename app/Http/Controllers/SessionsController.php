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
            session()->flash('success','登录成功');
            return redirect()->intended(route('users.show',[Auth::user()]));
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
