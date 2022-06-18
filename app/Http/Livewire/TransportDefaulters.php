<?php

namespace App\Http\Livewire;
use App\Models\Batch;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TransportDefaulters extends LivewireDatatable
{
    // public $complex = true;
    private static function genQuery()
    {
        /*
        select b.admission_no, b.full_name, c.batch_name, a.start_date, monthname(m.month) as unpaid 
        from 
        (
        select student_id, status,start_date,date(start_date - day(start_date) +1) as strt_date
        from transport_student_point 
        ) as a
        inner join student as b on a.student_id = b.id 
        inner join batch as c on b.class_id = c.id 
        inner join course as d on c.class_id = d.id 
        left join 
        (select '2021-11-01'as month
        union select '2021-12-01' as month
        union select '2022-01-01' as month 
        union select '2022-02-01' as month
        ) 
        as m on a.strt_date <= m.month 
        left join transport_feecollection as e on a.student_id = e.student_id 
        and m.month = e.month 
        where a.status = 1 and e.month is null 
        and m.month is not null 
        order by c.batch_name asc, b.full_name asc, m.month asc
        */

        $union="";
        if (request()->filled('start_date') && request()->filled('end_date')){
            $start_date = request('start_date');
            $end_date = request('end_date');
            $start = Carbon::createFromFormat('d/m/Y',$start_date)->startOfMonth();
            $end = Carbon::createFromFormat('d/m/Y',$end_date)->startOfMonth();
            
            while ($end >= $start) {
                if($union == ""){
                    $union = "'" . $start->format('Y-m-d') . "' as month ";
                }
                else{
                    $union .= "union select '" . $start->format('Y-m-d') . "' as month ";
                }
                $start = $start->addMonth(1);
            }
            $union = trim($union);
            session(['transport_defaulters.union' => $union]);

        }
        /*works while clicking page links*/
        else{
            $union = session('transport_defaulters.union');
        }

        // dd($union);

        return Student::fromsub(function($query){
            $query->select("student_id", "status","start_date",DB::raw("date(start_date - day(start_date) +1) as strt_date"))
                ->from("transport_student_point");
        },"a")
        ->join("student as b","a.student_id","b.id")
        ->join("batch as c","b.class_id","c.id")
        ->join("course as d","c.class_id","d.id")
        ->leftjoinsub(function($query) use ($union){
            $query->selectraw($union);
        },"m","a.strt_date","<=","m.month")
        ->leftjoin("transport_feecollection as e",function($join){
            $join->on("a.student_id","e.student_id")
                ->on("m.month","e.month");
                
        })
        ->where("a.status",1)
        ->wherenull("e.month")
        ->wherenotnull("m.month");
        
    }

    public function __construct() {
        // self::genQuery()->select('c.id')->dd();
        $ids = self::genQuery()->select('c.id')->pluck('c.id');
        session(['transport_defaulters.batchids'=>$ids]);
    }
    
    public function builder()
    {
        // $query = self::genQuery()
        //     ->select('b.admission_no','b.full_name','c.batch_name','a.start_date','m.month','m.mon')
        //     ->selectRaw("monthname(m.month)as unpaid")
        //     ->orderby("d.id")
        //     ->orderby("c.batch_name")
        //     ->orderby("b.full_name")
        //     ->orderby("m.month");
        
        // $query->dd();

        return self::genQuery()
                    // ->select('b.admission_no','b.full_name','c.batch_name','a.start_date','m.month')
                    // ->selectRaw("monthname(m.month)as unpaid")
                    ->orderby("d.id")
                    ->orderby("c.batch_name")
                    ->orderby("b.full_name")
                    ->orderby("m.month");
    }

    public function columns()
    {
        return [
            Column::name('b.admission_no')
                ->unwrap()
                ->label('Adm. No')
                ->searchable()
                ->unsortable(),
            
            Column::name('b.full_name')
                ->label('Full Name')
                ->searchable()
                ->unsortable(),
   
            Column::name('c.batch_name')
                ->label('Batch')
                ->filterable($this->batches)
                ->unsortable(),
                

            DateColumn::name('a.start_date')
                ->label('Start Dt.')
                ->unsortable(),


            Column::raw('monthname(m.month)')
                ->label('Month')
                ->filterable()
                ->unsortable(),
            
        ];
    }

    public function getBatchesProperty()
    {
        $ids = session('transport_defaulters.batchids');
        if($ids){
            return Batch::from("batch as b")
                ->join("course as c","b.class_id","c.id")
                ->wherein('b.id',$ids)
                ->orderby("c.id")
                ->orderby("b.batch_name")
                ->pluck('b.batch_name');
        }
        return [];
    }
}