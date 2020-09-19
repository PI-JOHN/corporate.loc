<?php

namespace App\Http\Controllers;

use App\Menu;

use App\Repositories\MenusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class ContactsController extends SiteController
{
    //
    public function __construct()
    {
        parent::__construct(new MenusRepository(new Menu));


        $this->bar = 'left';
        $this->template = Config::get('settings.theme').'.contacts';
    }

    public function index(Request $request)
    {

        if ($request->isMethod('post')) {

            $messages = [
                'required' => 'Поле :attribute Обязательно к заполнению',
                'email'    => 'Поле :attribute должно содержать правильный email адрес',
            ];

            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'text' => 'required'
            ]/*,$messages*/);

            $data = $request->all();

            $result = Mail::send(Config::get('settings.theme').'.email',['data'=>$data], function ($m) use ($data) {
                $mail_admin = env('MAIL_ADMIN');

                $m->from($data['email'], $data['name']);

                $m->to($mail_admin, 'Mr. Admin')->subject('Question');
            });

            if($result) {
                return redirect()->route('contacts')->with('status', 'Email is send');
            }

        }


        $this->title = 'Контакты';
        $content = view(Config::get('settings.theme').'.contact_content')->render();
        $this->vars = Arr::add($this->vars,'content',$content);

        $this->contentLeftBar = view(Config::get('settings.theme').'.contact_bar')->render();

        return $this->renderOutput();
    }
}
