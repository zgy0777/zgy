<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//引入User模型，并在控制器依赖注入User，可携带参数通过blade模版传递到视图上
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
class UsersController extends Controller
{
    public function __construct(){
        //TODO::设置中间件，except以下方法无需过滤，其他均需
        //TODO::except了show。未登录应可显示其他人的主页
        //只允许未登录用户访问注册/登录/查看用户主页
        $this->middleware('auth',[
            //控制器中添加 confirmEmail动作，允许未认证用户查看该视图
            'except'=>['store','show','create','index','confirmEmail']
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

    public function show(User $user)
    {
        $statuses = $user->statuses()
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        return view('users.show', compact('user', 'statuses'));
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


        $this->sendEmailConfirmationTo($user);
        session()->flash('success','邮件已发送到你的注册邮箱上，请注意查收');
        return redirect('/');

//        //TODO::渲染跳转
//        Auth::login($user);
//        session()->flash('success','欢迎，您将在此开启一段新的旅程');
//        //此时的user会自动回去MODEL的主键
//        return redirect()->route('users.show',[$user]);
    }

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        //根据路由传输过来的ACTIONVATION_TOKEN在数据库中查找相应用户
        //ELO的WHERE方法接收两个参数，第一个查找字段名称，第二个位对应的值。返回结果是一个数组
        //使用fristOrFail取出第一个用户，查询不到则404 not found
        $user = User::where('activation_token', $token)->firstOrFail();
        //若查询到用户是，令牌激活设置为true
        $user->activated = true;
        //并将激活令牌设为null
        $user->activation_token = null;
        //保存
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
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


