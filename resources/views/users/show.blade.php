@extends('layouts.default')
@section('title',$user->name)
@section('content')
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="col-md-12">
                <div class="col-md-offset-2 col-md-8">
                    <section class="user_info">
                        {{--引入头像组件，讲视图中接收的User实例化模型，传递到局部组件中--}}
                        @include('shared._user_info', ['user' => $user])
                    </section>
                </div>
            </div>
            <div class="col-md-12">
                @if (count($statuses) > 0)
                    <ol class="statuses">
                        @foreach ($statuses as $status)
                            @include('statuses._status')
                        @endforeach
                    </ol>
                    {!! $statuses->render() !!}
                @endif
            </div>
        </div>
    </div>
@stop