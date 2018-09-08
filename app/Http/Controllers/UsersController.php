<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//引入User模型，并在控制器依赖注入User，可携带参数通过blade模版传递到视图上
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UsersController extends Controller
{
    public function __construct(){
        //TODO::设置中间件，except以下方法无需过滤，其他均需
        //TODO::except了show。未登录应可显示其他人的主页
        //只允许未登录用户访问注册/登录/查看用户主页
        $this->middleware('auth',[
           'except'=>['store','show','create','index']
     ]);

        //TODO::只允许guest访问create方法，其他都不允许
        $this->middleware('guest',[
           'only'=>['create',]
        ]);

    }

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

    public function edit(User $user){

        $this->authorize('update',$user);
        return view('users.edit',compact('user'));

    }

    public function update(User $user,Request $request){
        //todo:形参接收两个参数，一个为edit界面提交的，另一个为将参数提交至封装好的Validate验证数据

        //验证
        $this->validate($request,[
            'name'=>'required|max:50',
            'password'=>'nullable|confirmed|min:6'
        ]);

        $this->authorize('update',$user);

        //逻辑

        $data=[];
        $data['name']=$request->name;
        if($request->password){
            $data['password']=bcrypt($request->password);
        }

        $user->update($data);

        //渲染,进入show页面需要代入id，route第二个参数将自动插入到url中
        session()->flash('success','个人资料更新成功');
        return redirect()->route('users.show',$user->id);
    }


    public function index(){
        //获取所有User模型的实例数据
        $users = User::paginate(10);

        return view('users.index',compact('users'));

    }

    public function destroy(User $user){
        //授权：只有管理员(1)才能删除用户
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户');
        return back();

    }

}
