<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;//route
use Illuminate\Foundation\Validation\ValidatesRequests;//validate
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;//AUTH

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
