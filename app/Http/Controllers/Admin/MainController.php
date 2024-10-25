<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MainController extends Controller
{
    public $pageData        = [];
    public $per_page        = 15;
    public $userInfo        = [];
    public $page_content    = '';
    public $page_title      = 'Admin Dashboard';
    public $metaDescription = '';
    public $metaKeyWords    = '';
    public $ogTitle         = 'test';
    public $ogImage         = '';
    public $ogDescription   = '';

    public function __construct()
	{
        
    }

    public function render_view($view_parth = null, $template='main')
    {
        if(!empty($view_parth))
        {
            if($template && !empty($template))
            {
                $this->pageData['page_content'] = view('admin.'.$view_parth,$this->pageData);
               // dd($this->pageData['page_content']);
               
               $this->pageData['page_title'] = $this->page_title;

                return view('admin.layouts.'.$template,$this->pageData);
                // $html =  view('admin.layout.'.$template,$this->pageData)->render();

                //dd($html);
            }
            else
            {
                return view('admin.'.$view_parth,$this->pageData)->render();
            }
        }
        else
        {
              dd('View Not Found');  
        }        
    }
}
