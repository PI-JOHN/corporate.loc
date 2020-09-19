<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;

class IndexController extends AdminController
{
    //

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if(Gate::denies('VIEW_ADMIN')) {
                abort(403);
            }
            return $next($request);
        });


        $this->template = Config::get('settings.theme').'.admin.index';
    }


    public function index()
    {

        $this->title = 'Панель администратора';

        return $this->renderOutput();
    }
}
