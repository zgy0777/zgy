<li id="status-{{ $status->id }}">
    <a href="{{ route('users.show', $user->id )}}">
        <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar"/>
    </a>
    <span class="user">
    <a href="{{ route('users.show', $user->id )}}">{{ $user->name }}</a>
  </span>
    <span class="timestamp">
    {{ $status->created_at->diffForHumans() }}
  </span>
    <span class="content">{{ $status->content }}</span>
    {{--新增删除权限，需要在控制器授权--}}
    @can('destroy',$status)
        {{--表单提交到 statuses控制器下的destroy方法，并且传入该该微博实例的id--}}
        <form action="{{route('statuses.destroy',$status->id)}}" method="post" >
            {{csrf_field()}}
            {{--RESTful删除是隐藏方法，POST不支持，需要额外指定--}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-sm btn-danger status-delete-btn" >
                删除
            </button>
        </form>
    @endcan


</li>