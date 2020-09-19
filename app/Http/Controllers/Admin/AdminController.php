<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Lavary\Menu\Menu;

class AdminController extends Controller
{
    //
    protected $p_rep;
    protected $a_rep;
    protected $articles;
    protected $user;
    protected $template;
    protected $content = FALSE;
    protected $title;
    protected $vars;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();
            if(!$this->user){
                abort(403);
            }
            return $next($request);
        });

        //$this->user = Auth::user();

//        if(!$this->user){
//            abort(403);
//        }
    }


    public function renderOutput()
    {
        $this->vars = Arr::add($this->vars,'title',$this->title);

        $menu = $this->getMenu();
        $navigation = view(Config::get('settings.theme').'.admin.navigation')->with('menu',$menu)->render();
        $this->vars = Arr::add($this->vars,'navigation',$navigation);

        if($this->content) {
            $this->vars = Arr::add($this->vars,'content',$this->content);
        }

        $footer = view(Config::get('settings.theme').'.admin.footer')->render();
        $this->vars = Arr::add($this->vars,'footer',$footer);

        return view($this->template)->with($this->vars);
    }


    public function getMenu()
    {
        return $menuBuilder = (new Menu)->make('adminMenu', function($menu){

            if(Gate::allows('VIEW_ADMIN_ARTICLES')) {
                $menu->add('Статьи',['route' => 'admin.articles.index']);
            }


            $menu->add('Портфолио',['route' => 'admin.articles.index']);
            $menu->add('Меню',['route' => 'admin.menus.index']);
            $menu->add('Пользователи',['route' => 'admin.users.index']);
            $menu->add('Привелегии',['route' => 'admin.permissions.index']);

        });
    }
}


