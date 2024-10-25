<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordVerifyLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        if (Auth::guard('admin')->check()) 
		{
            return redirect('admin/dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $validate = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $validate['role_id'] = [1,2];

        if(Auth::guard('admin')->attempt($validate))
        {
            $user = Auth::guard('admin')->user();

            if($user->is_active)
            {
                if($user->is_verify)
                {
                    User::where('id', $user->id)->update(['last_login_time'=>date('Y-m-d H:i:s')]);
                   return redirect()->intended('admin/dashboard');
                }
                else
                {
                    return back()->with('error','Your account are not verify.Contact Admin!'); 
                }
            }
            else
            {
                return back()->with('error','Your account are not active.Contact Admin!');
            } 
        }
        else
        {
            return back()->with('error','Whoops! invalid email and password.');
        }

        /*
         $user = User::where(['email' => $request->input('email'), 'role_id' => 1 ])->first();
      
        if($user && !empty($user))
        {
            if(!Hash::check($request->password,$user->password))
            {
                return back()->with('error','Whoops! you entered invalid password')->withInput($request->only('email'));
            }

            if($user->is_active != 1)
            {
                return back()->with('error','Your account are not active.Contact Admin!')->withInput($request->only('email'));
            }

            if($user->is_verify != 1)
            {
                return back()->with('error','Your account are not verify.Contact Admin!')->withInput($request->only('email')); 
            }

            session('admin_dashboard_id',$user->id);
            return redirect('admin/dashboard');
        }
        else
        {
            return back()->with('error','Whoops! you entered invalid email.')->withInput($request->only('email'));
        }
        */
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    
        //$request->session()->invalidate();
        //$request->session()->regenerateToken();
    
    }

    public function sendForgotPasswordLink(Request $request)
    {
        $validate = $this->validate($request,[
            'email' => 'required:email' 
        ]);

        if(!$user =  User::where(['email' => $request->input('email')])->first())
        {
            return Redirect::back()->with('error_msg','Invalid user please try again!');
        }
        
        $user->token = Str::random(60);
        $user->save();
        if(Mail::to($user->email)->send(new ForgotPasswordVerifyLink($user)))
        {
            return redirect('admin/login')->with('virifySuccessMsg', "Check your email for the confirmation email. It will have a link to reset your password");
        }
        else
        {
            return redirect('admin/login')->with('error','Something want to wrong mail not send please try again!');
        }
        
    }

    /**
     * 
     */
    public function verifyForgotPasswordLink(Request $request)
    {
        if(empty($request->input('__token')) && empty($request->input('rId')))
        {
            return redirect('admin/login')->with('error','something want to wrong please try again!');
        }

        if(!$user =  User::where(['id' => $request->input('rId'),'token' => $request->input('__token')])->first())
        {
            return redirect('admin/login')->with('error','look like your request has expired please try again!!');
        } 

        $user->token = '';
        $user->save();
        
        // add here forgot password view file

    }

    /**
     * Check User email exist or not in database
     */
    public function checkexists(Request $request)
    {
        $email = $request->input("email");
    	$result = User::where(['email' => $email,'role_id' => 1])->first();
    	if (!empty($result)){
    		echo "true";
    	}
    	else {
    		echo "false";
    	}
    	exit();
    }
}
