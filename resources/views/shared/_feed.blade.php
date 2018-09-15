{{--若该数组不为空，即已登录，返回该数组的所有内容。即该用户实例的所有微博--}}
@if (count($feed_items))
    <ol class="statuses">
        @foreach ($feed_items as $status)
            {{----}}
            @include('statuses._status', ['user' => $status->user])
        @endforeach
        {!! $feed_items->render() !!}
    </ol>
@endif