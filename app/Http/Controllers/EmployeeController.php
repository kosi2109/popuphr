<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;

class EmployeeController extends Controller
{

    public function index()
    {
        return view("employee.index");
    }

    public function ssd()
    {
        $employee = User::with("department");
        return DataTables::of($employee)
        ->filterColumn('department_name',function($query, $keyword){
            $query->whereHas('department',function($q1) use($keyword) {
                $q1->where('title','like','%'.$keyword.'%');
            });
        })
        ->editColumn('profile_img',function($each){
            return "<div class='d-flex flex-column justify-content-center align-items-center'><img src='/storage/". $each->profile_img ."' alt='' class='profile-thumb' /> <p>".$each->name."</p></div>";
        })
        ->addColumn('department_name',function($each){
            return $each->department ? $each->department->title : "-";
        })
        ->addColumn('action',function($each){
            $eidt = "<a href='/employee/". $each->id ."/edit' class='text-decoration-none'>
            <button class='btn btn-sm btn-outline-warning' style='width:40px' ><i class='fa-solid fa-pen-to-square'></i></button></a>";
            $info = "<a href='/employee/". $each->id ."/show' class='text-decoration-none'>
            <button class='btn btn-sm btn-outline-primary' style='width:40px'><i class='fa-solid fa-info'></i></button></a>";
            $delete = "
            <button data-id=". $each->id ." class='btn btn-sm btn-outline-danger delete' style='width:40px'><i class='fa-solid fa-trash-alt'></i></button>";
            return "<div>$eidt $info $delete</div>";
        })
        ->editColumn('is_present',function($each){
            if($each->is_present == 1){
                return '<span class="badge rounded-pill bg-success">Present</span>';
            }else{
                return '<span class="badge rounded-pill bg-danger">Leaft</span>';
            };
        })
        ->editColumn('updated_at',function($each){
            return Carbon::parse($each->updated_at)->format('d-m-Y H:i:s');
           
        })
        ->rawColumns(['profile_img','is_present','action'])
        ->make(true);
    }

    public function create(){
        $departments = Department::all()->sortBy('title');
        return view('employee.create',[
            "departments" => $departments
        ]);
    }

    public function store(){
        $image = null;
        if(request()->hasFile('profile_img')){
            $image = request()->file('profile_img')->store('employee');
        }
        
        $user = request()->validate([
            "employee_id" => ["required",Rule::unique('users','employee_id')],
            "name" => ["required"],
            "password" => ["required"],
            "phone" => ["required",Rule::unique('users','phone')],
            "email" => ["required",Rule::unique('users','email')],
            "nrc_number" => ["required",Rule::unique('users','nrc_number')],
            "gender" => ["required"],
            "birthday" => ["required"],
            "address" => ["required"],
            "department_id" => ["required",Rule::exists('departments','id')],
            "date_of_join" => ["required"],
            "is_present" => ["required"],
        ]);
        $user["password"] = Hash::make(request("password"));
        $user["profile_img"] = $image;
        User::create($user);
        return redirect("/employee")->with("success","User has been successfully created .");
    }

    public function edit(User $user){
        $departments = Department::all()->sortBy('title');
        
        return view('employee.edit',[
            "employee"=>$user,
            "departments"=>$departments
        ]);
    }
    
    public function update(User $user){
        if(request()->hasFile('profile_img')){
            $image = request()->file('profile_img')->store('employee');
            $user["profile_img"] = $image;
        }
        $formData = request()->validate([
            "employee_id" => ["required",Rule::unique('users','employee_id')->ignore($user->id)],
            "name" => ["required"],
            "phone" => ["required",Rule::unique('users','phone')->ignore($user->id)],
            "email" => ["required",Rule::unique('users','email')->ignore($user->id)],
            "nrc_number" => ["required",Rule::unique('users','nrc_number')->ignore($user->id)],
            "gender" => ["required"],
            "birthday" => ["required"],
            "address" => ["required"],
            "department_id" => ["required",Rule::exists('departments','id')],
            "date_of_join" => ["required"],
            "is_present" => ["required"]
        ]);
        
        if(isset(request()->password)){
            $user->password = Hash::make(request()->password) ;
        };
        
        foreach($formData as $key=>$value){
            $user->$key = $value;
        };
        
        $user->save();
        return redirect('/employee')->with("success","Employee has been successfully updated .");
    }

    public function show(User $user){
        return view('employee.show',[   
            'user'=> $user
        ]);
    }

    public function destory(User $user){
        $user->delete();
        return 'success';
    }

}
