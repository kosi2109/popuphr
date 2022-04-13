<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Http\Controllers\Auth\WebAuthnLoginController;
use App\Http\Controllers\BiomatericAttedanceController;
use App\Http\Controllers\MyAttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SalaryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('webauthn/register/options', [WebAuthnRegisterController::class, 'options'])
     ->name('webauthn.register.options');
Route::post('webauthn/register', [WebAuthnRegisterController::class, 'register'])
     ->name('webauthn.register');

Route::post('webauthn/login/options', [WebAuthnLoginController::class, 'options'])
     ->name('webauthn.login.options');
Route::post('webauthn/login', [WebAuthnLoginController::class, 'login'])
     ->name('webauthn.login');




Route::middleware("auth")->group(function(){
    Route::get('/', function () {
        return view('index');
    });
    // profile
    Route::delete('biodata/{id}/delete',[ProfileController::class,"bioDestroy"]);
    Route::get('profile',[ProfileController::class,"show"])->middleware('permission:view_profile');
    Route::get('profile/bio-data',[ProfileController::class,"bioData"])->middleware('permission:view_profile');

    // employee
    Route::get('employee',[EmployeeController::class,"index"])->middleware('permission:view_employee');
    Route::get('employee/create',[EmployeeController::class,"create"])->middleware('permission:create_employee');
    Route::post('employee/store',[EmployeeController::class,"store"])->middleware('permission:create_employee');
    Route::get('employee/{user}/edit',[EmployeeController::class,"edit"])->middleware('permission:edit_employee');
    Route::get('employee/{user}/show',[EmployeeController::class,"show"])->middleware('permission:view_employee');
    Route::post('employee/{user}/update',[EmployeeController::class,"update"])->middleware('permission:edit_employee');
    Route::delete('employee/{user}/delete',[EmployeeController::class,"destroy"])->middleware('permission:delete_employee');
    Route::get('employee/database/ssd',[EmployeeController::class,"ssd"])->middleware('permission:view_employee|create_employee|edit_employee|delete_employee');

    // department
    Route::get('department',[DepartmentController::class,"index"])->middleware('permission:view_department');
    Route::get('department/create',[DepartmentController::class,"create"])->middleware('permission:create_department');
    Route::post('department/store',[DepartmentController::class,"store"])->middleware('permission:create_department');
    Route::get('department/{department}/edit',[DepartmentController::class,"edit"])->middleware('permission:edit_department');
    Route::post('department/{department}/update',[DepartmentController::class,"update"])->middleware('permission:edit_department');
    Route::delete('department/{department}/delete',[DepartmentController::class,"destroy"])->middleware('permission:delete_department');
    Route::get('department/database/ssd',[DepartmentController::class,"ssd"])->middleware('permission:view_department|create_department|edit_department|delete_department');


    // role
    Route::get('role',[RoleController::class,"index"])->middleware('permission:view_role');
    Route::get('role/create',[RoleController::class,"create"])->middleware('permission:create_role');
    Route::post('role/store',[RoleController::class,"store"])->middleware('permission:create_role');
    Route::get('role/{role}/edit',[RoleController::class,"edit"])->middleware('permission:edit_role');
    Route::post('role/{role}/update',[RoleController::class,"update"])->middleware('permission:edit_role');
    Route::delete('role/{role}/delete',[RoleController::class,"destroy"])->middleware('permission:delete_role');
    Route::get('role/database/ssd',[RoleController::class,"ssd"])->middleware('permission:view_role|create_role|edit_role|delete_role');

    // permission
    Route::get('permission',[PermissionController::class,"index"])->middleware('permission:view_permission');
    Route::get('permission/create',[PermissionController::class,"create"])->middleware('permission:create_permission');
    Route::post('permission/store',[PermissionController::class,"store"])->middleware('permission:create_permission');
    Route::get('permission/{permission}/edit',[PermissionController::class,"edit"])->middleware('permission:edit_permission');
    Route::post('permission/{permission}/update',[PermissionController::class,"update"])->middleware('permission:edit_permission');
    Route::delete('permission/{permission}/delete',[PermissionController::class,"destroy"])->middleware('permission:delete_permission');
    Route::get('permission/database/ssd',[PermissionController::class,"ssd"])->middleware('permission:view_permission|create_permission|edit_permission|delete_permission');
    
    
    // company
    Route::get('company/{company}/show',[CompanyController::class,"show"])->middleware('permission:view_company');
    Route::get('company/{company}/edit',[CompanyController::class,"edit"])->middleware('permission:edit_company');
    Route::post('company/{company}/update',[CompanyController::class,"update"])->middleware('permission:edit_company');

    // attendance
    Route::get('attendance',[AttendanceController::class,"index"])->middleware('permission:view_attendance');
    Route::get('attendance/create',[AttendanceController::class,"create"])->middleware('permission:create_attendance');
    Route::post('attendance/store',[AttendanceController::class,"store"])->middleware('permission:create_attendance');
    Route::get('attendance/{attendance}/show',[AttendanceController::class,"show"])->middleware('permission:view_attendance');
    Route::get('attendance/{attendance}/edit',[AttendanceController::class,"edit"])->middleware('permission:edit_attendance');
    Route::post('attendance/{attendance}/update',[AttendanceController::class,"update"])->middleware('permission:edit_attendance');
    Route::delete('attendance/{attendance}/delete',[AttendanceController::class,"destroy"])->middleware('permission:delete_attendance');
    Route::get('attendance/database/ssd',[AttendanceController::class,"ssd"])->middleware('permission:view_attendance|create_attendance|edit_attendance');
    Route::get('attendance/overview',[AttendanceController::class,"overView"])->middleware('permission:view_attendance');
    Route::get('attendance/overview-table',[AttendanceController::class,"overViewTable"])->middleware('permission:view_attendance');


     //my-attendance
     Route::get('my-attendance',[MyAttendanceController::class,"overView"]);
     Route::get('my-attendance/overview-table',[MyAttendanceController::class,"overViewTable"]);
     Route::get('my-attendance/database/ssd',[MyAttendanceController::class,"ssd"]);
     Route::get('my-payroll-table',[MyAttendanceController::class,"payrollTable"]);

     // salary
    Route::get('salary',[SalaryController::class,"index"])->middleware('permission:view_salary');
    Route::get('salary/create',[SalaryController::class,"create"])->middleware('permission:create_salary');
    Route::post('salary/store',[SalaryController::class,"store"])->middleware('permission:create_salary');
    Route::get('salary/{salary}/edit',[SalaryController::class,"edit"])->middleware('permission:edit_salary');
    Route::post('salary/{salary}/update',[SalaryController::class,"update"])->middleware('permission:edit_salary');
    Route::delete('salary/{salary}/delete',[SalaryController::class,"destroy"])->middleware('permission:delete_salary');
    Route::get('salary/database/ssd',[SalaryController::class,"ssd"])->middleware('permission:view_salary|create_salary|edit_salary|delete_salary');
    
     // payroll
    Route::get('payroll',[PayrollController::class,"payroll"])->middleware('permission:view_payroll');
    Route::get('payroll-table',[PayrollController::class,"payrollTable"])->middleware('permission:view_payroll');

});


Route::get('login-option',[ProfileController::class,"loginOption"]);


// for biomateric machine

Route::get('bio-login',[BiomatericAttedanceController::class,'login']);
Route::post('bio-login',[BiomatericAttedanceController::class,'store']);
Route::get('check-users',[BiomatericAttedanceController::class,'checkUsers'])->middleware('bio');
Route::post('check-in-out',[BiomatericAttedanceController::class,'check_in_out'])->middleware('auth')->middleware('permission:view_check_in_out');
Route::get('check-in-out',[BiomatericAttedanceController::class,'attendance'])->middleware('auth')->middleware('permission:view_check_in_out');





require __DIR__.'/auth.php';
