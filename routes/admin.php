<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\UserProfileController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;



//Auth::routes();
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {


    Route::group(['middleware'=>'adminGuest'],function () {
        Route::get('/', [LoginController::class, 'index'])->name('adminLogin');
        Route::get('login', [LoginController::class, 'index'])->name('adminLogin');
        Route::post('admin-login', [LoginController::class, 'login'])->name('admin-login');
        
        
        Route::post('/login/checkexists', [LoginController::class, 'checkexists'])->name('auth.check-user-email');
        Route::post('send-forgot-password-link', [LoginController::class, 'sendForgotPasswordLink'])->name('send-forgot-password-link');
        Route::get('login/reset-password-link', [LoginController::class, 'verifyForgotPasswordLink'])->name('reset-password-link');
    });

    //
    Route::group(['middleware' => 'adminAuth'],function(){

        Route::get('logout', [LoginController::class, 'logout']);
		Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        /* Change active status */
        Route::post('/dashboard/changeStatus', [DashboardController::class, 'changeStatus'])->name('change-status');

    });

    Route::group(['middleware' => ['adminAuth','hasPermission']],function(){

        // Manage roles 
        Route::get('/user-roles', [UserRoleController::class, 'index'])->name('user-roles');
        Route::get('/userRole/getModuleAccessByRole', [UserRoleController::class, 'getModuleAccessByRole'])->name('userRole.get-module-access-by-role');
        Route::post('/admin/userRole/addRolePermission', [UserRoleController::class, 'addRolePermission'])->name('userRole.add-role-permission');
        
        
        

        
    
    
        /* Users */
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/add-user-modal', [UserController::class, 'addUserModal'])->name('add-user-modal');
        Route::post('/add-user', [UserController::class, 'addUser'])->name('add-user');
        Route::post('/delete-user', [UserController::class, 'deleteUser'])->name('delete-user');
        Route::post('/user/checkexists', [UserController::class, 'checkexists'])->name('check-user-email');
        Route::post('/user/changeStatus', [UserController::class, 'changeStatus'])->name('change-user-status');
    
        /* User Profile */
        Route::get('/user/profile', [UserProfileController::class, 'profile'])->name('user-profile');
        Route::post('/user/update-profile', [UserProfileController::class, 'updateProfile'])->name('update-user-profile');
        Route::post('/change-password-modal', [UserProfileController::class, 'changePasswordModal'])->name('change-password-modal');
        Route::post('/check-user-password', [UserProfileController::class, 'checkPassword'])->name('check-user-password');
        Route::post('/change-password', [UserProfileController::class, 'changePassword'])->name('change-password');
    
    
        //* Category */
        Route::get('/category', [CategoryController::class, 'index'])->name('category');
        Route::post('/add-category-modal', [CategoryController::class, 'addCategoryModal'])->name('add-category-modal');
        Route::post('/add-category', [CategoryController::class, 'add'])->name('add-category');
        Route::post('/category-delete', [CategoryController::class, 'delete'])->name('delete-category');
        Route::post('/category/checkexists', [CategoryController::class, 'checkexists'])->name('check-category-name');
        Route::post('/category/changeStatus', [UserController::class, 'changeStatus'])->name('change-category-status');


        //* Products */
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::post('/add-product-modal', [ProductController::class, 'addProductModal'])->name('add-product-modal');
        Route::post('/add-product', [ProductController::class, 'add'])->name('add-product');
        Route::post('/delete-product', [ProductController::class, 'delete'])->name('delete-product');
        Route::post('/category/checkexists', [ProductController::class, 'checkexists'])->name('check-category-name');
        
        
    });

   
});

?>