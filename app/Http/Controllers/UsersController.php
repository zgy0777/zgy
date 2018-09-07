<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//引入User模型，并在控制器依赖注入User，可携带参数通过blade模版传递到视图上
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UsersController extends Controller
{
    //注册页
    public function create(){
        return view('users.create');
    }

    public function show(User $user){


        return view('users.show',compact('user'));
    }

    public function store(Request $request){
        //验证
        $this->validate($request,[
            'name'=>'required|min:3|max:6',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|confirmed|min:6'
        ]);
        //TODO::逻辑
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        //TODO::渲染跳转
        Auth::login($user);
        session()->flash('success','欢迎，您将在此开启一段新的旅程');
        //此时的user会自动回去MODEL的主键
        return redirect()->route('users.show',[$user]);
    }


}
