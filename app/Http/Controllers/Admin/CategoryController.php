<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\admin\MainController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends MainController
{
    //
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

        $query = Category::select('*')->where($where)->where('is_deleted','!=',1);

        if(!empty($search_text))
        {
            $query = $query->where('name','like','%'.$search_text.'%');
        }    

        $categorys = $query->orderBy($order_by,$order)->paginate($this->per_page);
        

        $this->pageData['categorys'] = $categorys;
        $this->page_title = 'Category';
        return $this->render_view('category.index');
    }

    /**
     * view modal box for add user form 
    */
    public function addCategoryModal(Request $request)
    {
        $id   = $request->id;
    	$category = [];
        
    	if($id && $id!='')
    	{
    		$category = Category::where(['id' => $id])->first();
    	}
    	
    	$this->pageData['category'] = $category;
    	$this->render_view('category/add-category',FALSE);
    }

    /**
     * Save user in database
     */
    public function add(Request $request)
    {
        $category = new Category();
        if($request->input('category_id') && !empty($request->input('category_id')))
        {
            $category = Category::where(['id' => $request->input('category_id')])->first();
        }
        
        $category->name = $request->input('name');


        $msg = 'Category added successfully';
        if($request->input('category_id') && !empty($request->input('category_id')))
        $msg = 'Category updated successfully';

        if($category->save())
        {
            return redirect('admin/category')->with('success_msg',$msg);
        }
        else
        {
            return redirect('admin/category')->with('error_msg','Something went to wrong please try again!'); 
        }

        return redirect('admin/category')->with('error_msg',$msg);
    }

    /**
     * Delate user from database (soft delete functionality)  
     */
    public function delete(Request $request)
    {
        $res_output = array('code'=>1001,'status'=>"error","message"=>"Category not delete, please try again");
        if($request->input('category_id'))
    	{
    		$category_id = $request->input('category_id');

    		$category = Category::where(['id' => $category_id])->first();
    		if(!empty($category))
    		{
                $category->is_deleted = 1;
                $result = $category->save();
                if($result){
                    $res_output['code'] = 1000;
                    $res_output['status'] = "success";
                    $res_output['message'] = "Category deleted successfully.";
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
