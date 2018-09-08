<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */

    public function __construct()
    {
        //
    }

    public function update(User $currentUser,User $user){
        return $currentUser->id === $user->id;
    }

    public function destroy(User $currentUser,User $user){
        //执行删除时，当前用户实例必须是admin（1）并且该id不是自己时，才能执行删除
        //TODO::控制器中引入$this->authorize('destroy',$user)
        //TODO::视图中删除按钮 使用@can 必须是管理员才能删除
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }

}
