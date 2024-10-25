<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModulePermission;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends MainController
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function index(Request $request)
    {
        $role_data  = UserRole::where(['is_active' => '1'])->where('role_constant','!=','ADMIN')->get();
    	$moduleList = ModulePermission::where(['is_active'=>1])->get();
      

        
    	$this->pageData['role_data']  = $role_data;
    	$this->pageData['moduleList'] = $moduleList;

        $this->page_title = 'User Roles';
        return $this->render_view('user-roles.index');  
    }

    public function addRolePermission(Request $request)
    {      
    	UserRole::saveRoleAccess($request);
        return redirect('admin/user-roles')->with('success_msg','Role permission updated successfully');
    }

    /**
     * get role access module
     */
    public function getModuleAccessByRole(Request $request)
    {
        $response = ['status' => 0, 'data' => []];
    	$permission = RolePermission::where(["role_id" => $request->input('role_id')])->get();

    	if (!empty($permission)) {
            $response = ['status' => 1, 'data' => $permission];
    		echo json_encode($response);exit();
    	} else {
    		echo json_encode($response);exit();
    	}
    }
}

