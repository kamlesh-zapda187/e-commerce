<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class SiteController extends Controller
{
    public $pageData        = [];
    public $per_page        = 15;
    public $userInfo        = [];
    public $page_content    = '';
    public $page_title      = 'Eshop - eCommerce';
    public $metaDescription = '';
    public $metaKeyWords    = '';
    public $ogTitle         = 'Eshop - eCommerce';
    public $ogImage         = '';
    public $ogDescription   = '';
    public $cartProducts    = [];

    public function __construct()
	{
       // dd('Site constructor called');
      // $cart = json_decode(Cookie::get('e_comm_product_cart','[]'));
      

    }

    public function render_view($view_parth = null, $template='main')
    {
        if(!empty($view_parth))
        {
            if($template && !empty($template))
            {
               $this->pageData['page_content'] = view($view_parth,$this->pageData);
               
                $this->pageData['page_title'] = $this->page_title;
                $this->pageData['cartProducts'] = $this->getCartProducts();

                return view('layout.'.$template,$this->pageData);

            }
            else
            {
                
                return view($view_parth,$this->pageData)->render();
            }
        }
        else
        {
              dd('View Not Found');  
        }        
    }

    public function getCartProducts(){
        $cart = json_decode(Cookie::get('shopping_cart')) ?? [];

        $cartProducts = [];
        if(!empty($cart)){
            foreach($cart as $cartKey => $cartItem){
                if($product = Product::where(['id'=>$cartItem->product_id])->first()){
                    $product->qty = $cartItem->qty;
                    $cartProducts[$cartKey] = $product;    
                }    
            }
        }

        return $cartProducts;

    }
}
