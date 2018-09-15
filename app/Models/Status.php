<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Status extends Model
{

    //批量注入字段（开放注入）
    protected $fillable = ['content'];

    //创建关联，绑定User模型;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
