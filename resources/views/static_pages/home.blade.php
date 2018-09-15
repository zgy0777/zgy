@extends('layouts.default')

@section('content')
  @if (Auth::check())
    <div class="row">
      <div class="col-md-8">
        <section class="status_form">
          {{--引入局部视图（微博发布界面）--}}
          @include('shared._status_form')
        </section>
        <h3>微博列表</h3>
        {{--引入局部视图（里面判断了用户是否已登录再取出数据）--}}
        @include('shared._feed')
      </div>
      <aside class="col-md-4">
        <section class="user_info">
          {{--引入局部视图，并且代入参数（当前登录用户实例的数据集）--}}
          @include('shared._user_info', ['user' => Auth::user()])
        </section>
      </aside>
    </div>
  @else
    <div class="jumbotron">
      <h1>光哥欢迎你</h1>
      <p class="lead">
        proto-yes(origin)-__-Heroku grenate Key(Base-64)
      </p>
      <p>
        npm@import-Path_________@Model->relation->return
      </p>
      <p>
        <a class="btn btn-lg btn-success" href="{{route('signup')}}" role="button">现在注册</a>
      </p>
    </div>
  @endif
@stop
