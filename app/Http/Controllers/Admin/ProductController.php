<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends MainController
{
    public function index(Request $request)
    {

        $search_text = $request->input('search_text');
        $status      = $request->input('status');

        $order_by = 'id';
        $order    = 'DESC' ;

        $where = [];

        if(!empty($status))
        {
            $where['is_active'] = ($status=='active') ? 1 : 0;
        }

        $query = Product::select('*')->where($where)->where('is_deleted','!=',1);

        if(!empty($search_text))
        {
            $query = $query->where('product_title','like','%'.$search_text.'%');
        }    

        $products = $query->orderBy($order_by,$order)->paginate($this->per_page);

        $this->pageData['products'] = $products;
        $this->page_title = 'Products';
        return $this->render_view('product.index');
    }

    
    /**
     * view modal box for add user form 
    */
    public function addProductModal(Request $request)
    {

        $id   = $request->id;
    	$product = [];
        //$this->pageData["categories"] = Category::map(['id','name'])->get();
        $this->pageData["categories"] =  (new Category())->get_categories();
        
    	if($id && $id!='')
    	{
    		$product = Product::where(['id' => $id])->first();
    	}
    	
    	$this->pageData['product'] = $product;
    	$this->render_view('product/add-product',FALSE);
    }

    /**
     * Save user in database
     */
    public function add(Request $request)
    {
        $product = new Product();
        if($request->input('product_id') && !empty($request->input('product_id')))
        {
            $product = Product::where(['id' => $request->input('product_id')])->first();
        }
        
        $product->product_title = $request->input('product_title');
        $product->category_id   = $request->input('category_id');
        $product->price         = $request->input('price');

        $product_image  = $request->file('product_image');
        if(!empty($product_image))
        {
            $originalname = $product_image->getClientOriginalName();
            $file_name    = time()."_".$originalname;
            $product_image->move('public/uploads/product/',$file_name);
            $product->product_image = $file_name;
        }

        $msg = 'Product added successfully';
        if($request->input('product_id') && !empty($request->input('product_id')))
        $msg = 'Product updated successfully';

        if($product->save())
        {
            return redirect('admin/products')->with('success_msg',$msg);
        }
        else
        {
            return redirect('admin/products')->with('error_msg','Something went to wrong please try again!'); 
        }

        return redirect('admin/products')->with('error_msg',$msg);
    }

    /**
     * Delate user from database (soft delete functionality)  
     */
    public function delete(Request $request)
    {
        $res_output = array('code'=>1001,'status'=>"error","message"=>"Product not delete, please try again");
        if($request->input('id'))
    	{
    		$id = $request->input('id');

    		$product = Product::where(['id' => $id])->first();
    		if(!empty($product))
    		{
                $product->is_deleted = 1;
                $result = $product->save();
                if($result){
                    $res_output['code'] = 1000;
                    $res_output['status'] = "success";
                    $res_output['message'] = "Product deleted successfully.";
                }
    		}
    	}

    	echo json_encode($res_output);exit();
    }

    /**
     * Check User email exist or not in database
     */
    public function checkexists(Request $request){
    	
        
        $value = $request->input('name');
    	$whr = array('name'=>$value);
    	
    	$query = Category::where($whr);
        if ($request->input('id'))
    	{
            $query = $query->where('id','!=',$request->input('id'));
        }    

        $result = $query->first();

    	if (empty($result)){
    		echo "true";
    	}
    	else {
    		echo "false";
    	}
    	exit();
    }
}
