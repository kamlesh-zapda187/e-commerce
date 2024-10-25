<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }



    /* get user profile details for view */
    public function profile()
    {
        if(!Auth::guard('admin')->check())
        {
            return redirect('admin/logout')->with('error','Invalid user please login to continue try again!');;
        }

        $user = User::where(['id' => Auth::guard('admin')->user()->id])->first();
        if(empty($user))
        {
            return Redirect::back()->with('error_msg','No user found please try again!');
        }

        $this->pageData['user'] = $user;
        $this->page_title = 'User Profile';
        return $this->render_view('user.user-profile');
    }

    public function updateProfile(Request $request)
    {
        $validation = $this->validate($request,[
            'first_name' => 'required',
            'last_name'  => 'required',
            'contact'    => 'required',
            'email'      => 'required:email',
        ]);

        $user_id = $request->input('user_id');
        if(empty($user_id) && $user_id != Auth::user()->id)
        {
            return Redirect::back()->with('error_msg','Invalid user please try again!');
        }

        if(!$user = User::where(['id' => $user_id])->first())
        {
            return Redirect::back()->with('error_msg','Invalid user please try again!');
        }

        $user->first_name = $request->input('first_name');
        $user->last_name  = $request->input('last_name');
        $user->full_name  = $request->input('first_name').' '.$request->input('last_name');
        $user->email      = $request->input('email');
        $user->contact    = $request->input('contact');

        $profile_image  = $request->file('profile_image');
        if(!empty($profile_image))
        {
            $originalname = $profile_image->getClientOriginalName();
            $file_name = time()."_".$originalname;
            $profile_image->move('public/uploads/user_profile/',$file_name);
            $user->user_avatar = $file_name;
        }

        /*
        if(!empty($request->profile_image) && $request->profile_image)
        {
            $fileName = time().'.'.$request->profile_image->extension();  
            $file = $request->file('profile_image')->storeAs('uploads/user_profile',$fileName);
            $user->user_avatar = $fileName;
        }
        */
        
        if($user->save())
        {
            return Redirect::back()->with('success_msg','Profile updated successfully');
        }
        
        return Redirect::back()->with('error_msg','Invalid user please try again!');
    }

    /**
     * Open change password modal view file
     */
    public function changePasswordModal(Request $request)
    {
        // $id   = $request->id;
    	$this->render_view('auth/change-password',FALSE);
    }

    public function checkPassword(Request $request)
    {
        $validation = $this->validate($request,[
            'old_password'=>'required',
        ]);

        $user = Auth::guard('admin')->user();
        if(empty($user->id))
        {
            echo "false";exit;
        }

        if (Hash::check($request->input('old_password'), $user->password)){
    		echo "true";exit;
    	}
    	else{
    		echo "false";exit;
    	}
    }

    /**
     * Change user password 
     */
    public function changePassword(Request $request)
    {
        $validation = $this->validate($request,[
            'old_password'=>'required',
            'new_password'=>'required',
        ]);

        if(!Auth::guard('admin')->check())
        {
            return Redirect::back()->with('error_msg','Auth user not found!');
        }

        $user =  User::where(['id' => Auth::guard('admin')->user()->id])->first();
        if(empty($user))
        {
            return Redirect::back()->with('error_msg','Invalid user please try again!');
        }

        if (Hash::check($request->input('new_password'), $user->password))
        {
            return Redirect::back()->with('error_msg',"new password can't be equal to the previous password please add different password!");
        }

        $user->password = Hash::make($request->input('new_password'));
        if($user->save())
        {
            return Redirect::back()->with('success_msg',"Password changed successfully");
        }
        else
        {
            return Redirect::back()->with('error_msg',"Failed to change password please try again!");
        }
    }

}
