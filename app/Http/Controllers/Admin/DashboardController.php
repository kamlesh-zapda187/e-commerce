<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\admin\MainController;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

      

        return $this->render_view('dashboard.admin-dashboard');  
    }

    /* use for commonly from every pages
       for change database record status  
    */
    public function changeStatus(Request $request)
    {
        $validate = $this->validate($request,[
            'update_id' => 'required',
            'status' =>'required',
            'table_name' =>'required',
        ]);

        $update_id = $request->input('update_id');
        $status = ($request->input('status') == 1) ? 0 : 1;
        $table_name = $request->input('table_name');

        $result = DB::table($table_name)
                ->where('id', $update_id)
                ->update(['is_active' => $status]);
        if(!empty($result))
        {
            $res_output['code']    = 1000;
            $res_output['status']  = "success";
            $res_output['message'] = "Status change successfully.";
        }
        else
        {
            $res_output['code']    = 1001;
            $res_output['status']  = "error";
            $res_output['message'] = "Status not change, please try again.";
        }

        echo json_encode($res_output);exit();
        
    }
}
