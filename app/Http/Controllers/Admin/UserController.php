<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends MainController
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function index(Request $request)
    {

        $search_text = $request->input('search_text');
        $status      = $request->input('status');

        $order_by = 'users.id';
        $order    = 'DESC' ;

        $where = ['users.role_id' => 2,'user_roles.is_active'=>1];

        if(!empty($status))
        {
            $where['users.is_active'] = $status;
        }

        $query = User::select('users.*','user_roles.name','user_roles.role_constant')
                 ->join('user_roles', 'users.role_id', '=', 'user_roles.id')
                 ->where($where)->where('users.is_deleted','!=',1);

        if(!empty($search_text))
        {
            $query = $query->where('users.first_name','like','%'.$search_text.'%')
                           ->orWhere('users.last_name','like','%'.$search_text.'%')               
                           ->orWhere('users.email','like','%'.$search_text.'%');
        }    

        $users = $query->orderBy($order_by,$order)->paginate($this->per_page);

       
        $this->pageData['users'] = $users;
        $this->page_title = 'Users';
        return $this->render_view('user.index');  
    }

    /**
     * view modal box for add user form 
    */
    public function addUserModal(Request $request)
    {
        $id   = $request->id;
    	$user = [];
        
    	if($id && $id!='')
    	{
    		$user = User::where(['id' => $id])->first();
    	}
    	
    	$this->pageData['user'] = $user;
    	$this->render_view('user/add-user',FALSE);
    }

    /**
     * Save user in database
     */
    public function addUser(Request $request)
    {
        $user = new User();
        if($request->input('user_id') && !empty($request->input('user_id')))
        {
            $user = User::where(['id' => $request->input('user_id')])->first();
        }
        
        $user->role_id    = 2;
        $user->first_name = $request->input('first_name');
        $user->last_name  = $request->input('last_name');
        $user->full_name  = $request->input('first_name').' '.$request->input('last_name');
        $user->email      = $request->input('email');
        $user->contact    = $request->input('contact');

        if(!empty($request->input('password')))
        {
            $user->password   = Hash::make($request->input('password'));    
        }

        $msg = 'User added successfully';
        if($request->input('user_id') && !empty($request->input('user_id')))
        $msg = 'User updated successfully';

        if($user->save())
        {
            return redirect('admin/users')->with('success_msg',$msg);
        }
        else
        {
            return redirect('admin/users')->with('error_msg','Something went to wrong please try again!'); 
        }

        return redirect('admin/users')->with('error_msg',$msg);
    }

    /**
     * Delate user from database (soft delete functionality)  
     */
    public function deleteUser(Request $request)
    {
        $res_output = array('code'=>1001,'status'=>"error","message"=>"User not delete, please try again");
        if($request->input('user_id'))
    	{
    		$user_id = $request->input('user_id');

    		$usernfo = User::where(['id' => $user_id])->first();
    		if(!empty($usernfo))
    		{
    			if($usernfo->user_avatar != '' && file_exists("uploads/user/".$usernfo->user_avatar))
    			{
    				unlink('upload/user/'.$usernfo->user_avatar);
    				unlink('upload/user/'.'thumb/'.$usernfo->user_avatar);
    			}

                $usernfo->is_deleted = 1;
                $result = $usernfo->save();
                if($result){
                    $res_output['code'] = 1000;
                    $res_output['status'] = "success";
                    $res_output['message'] = "User deleted successfully.";
                }
    		}
    	}

    	echo json_encode($res_output);exit();
    }

    /**
     * Check User email exist or not in database
     */
    public function checkexists(Request $request){
        $field = $request->input("name");
    	$value = $request->input($field);
    	$whr = array($field=>$value);
    	
    	$query = User::where($whr);
        if ($request->input('user_id'))
    	{
            $query = $query->where('id','!=',$request->input('user_id'));
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


