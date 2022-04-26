<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Batch extends Model
{
    use HasFactory;
    protected $table = 'batch';

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
    
}
