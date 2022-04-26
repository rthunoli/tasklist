<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Student extends Model
{
    use HasFactory;
    protected $table = 'student';

    public function __construct() {
        if(session()->has('db') && session('db')=='EMS'){
            $this->connection = 'mysql_ems';
            DB::setDefaultConnection('mysql_ems');

        }
        elseif(session()->has('db') && session('db')=='HSS'){
            $this->connection = 'mysql_hss';
            DB::setDefaultConnection('mysql_hss');
        }
    }

    public function parent()
    {
        return $this->belongsTo(Guardian::class,'parent_id')->where('status',1);

    }

}
