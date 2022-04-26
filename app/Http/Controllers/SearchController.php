<?php

namespace App\Http\Controllers;

// use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if(session()->has('db') && session('db')=='EMS'){
            $connection = 'mysql_ems';
        }
        elseif(session()->has('db') && session('db')=='HSS'){
            $connection = 'mysql_hss';
        }
        else{
            return back();
        }

        // $table = $request->table;
        $val = $request->search;

        DB::setDefaultConnection($connection);
        $query = DB::query();
        $columns= [
            'a.admission_no',
            'a.full_name',
            'a.gender',
            'a.phone_no',
            'a.phone_home',
            'a.admission_date',
            'a.admitted_class',
            'a.second_language',
            'b.father_name',
            'b.father_occupation',
            'b.phone_father',
            'b.father_residential_address',
            'b.mother_name',
            'b.mother_occupation',
            'b.phone_mother',
            'b.mother_residential_address',
            'b.guardian_name',
            'b.phone_guardian',
            'b.guardian_address',
        ];
        $query->from = 'student as a';
        $query->join('guardian as b','a.parent_id','b.id');

        $query->columns=$columns;
        $query->selectRaw('date_format(a.dob,"%d-%m-%Y") as dob');
        $query->selectRaw('date_format(a.admission_date,"%d-%m-%Y") as admission_date');
        $query->selectRaw('case a.status when 1 then "Active" when 3 then "Inactive" when 5 then "TC Issued" end as status');

        foreach($columns as $column){
            $query->orWhere($column, 'LIKE', '%' . $val . '%');
        }

        // $query->dd();
        $students = $query->paginate(10)->withQueryString();
        // dd($students);
        // return view('students',compact('students', $students));
        return view('students')->with('students',$students);
    }

    // public function old_index(Request $request)
    // {
    //     if(session()->has('db') && session('db')=='EMS'){
    //         $connection = 'mysql_ems';
    //     }
    //     elseif(session()->has('db') && session('db')=='HSS'){
    //         $connection = 'mysql_hss';
    //     }
    //     else{
    //         return back();
    //     }
    
    //     $table = $request->table;
    //     $val = $request->search;
    
    //     $columns = "admission_no,full_name,gender,
    //     date_format(dob,'%d-%m-%Y') as dob,
    //     phone_no,phone_home,
    //     date_format(admission_date,'%d-%m-%Y') as admission_date,
    //     case status
    //         when 1 then 'Active'
    //         when 3 then 'Inactive'
    //         when 5 then 'TC Issued'
    //     end as status,create_time,update_time,admitted_class,stream,first_name,middle_name,last_name,eligible_admission_category,reservation_category,age,second_language";

    //     $query = "select $columns from $table where (`admission_no` LIKE '%$val%' OR `full_name` LIKE '%$val%' OR `gender` LIKE '%$val%' OR `dob` LIKE '%$val%' OR `image` LIKE '%$val%' OR `phone_no` LIKE '%$val%' OR `phone_home` LIKE '%$val%' OR `admission_date` LIKE '%$val%' OR `create_time` LIKE '%$val%' OR `update_time` LIKE '%$val%' OR `admitted_class` LIKE '%$val%' OR `stream` LIKE '%$val%' OR `first_name` LIKE '%$val%' OR `middle_name` LIKE '%$val%' OR `last_name` LIKE '%$val%' OR `eligible_admission_category` LIKE '%$val%' OR `reservation_category` LIKE '%$val%' OR `age` LIKE '%$val%' OR `second_language` LIKE '%$val%')";

    //     $results = DB::connection($connection)->select($query);
    //     // dd($results);
        
    //     // convert array to collection with pagination
    //     $students = (new Collection($results))->paginate($request);

    //     // dd($students);

    //     // return view('students',compact('students', $students));
    //     return view('students')->with('students',$students);

    // }

}
