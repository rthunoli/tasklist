<?php

namespace App\Http\Livewire;
use App\Models\Batch;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TransportPaid extends LivewireDatatable
{
    private static function genQuery()
    {
        /*select batch_name,admission_no,full_name,month,amount
        from
        (
        select c.batch_name,b.admission_no,b.full_name,a.month as mo,monthname(a.month) as month,a.amount
        from transport_feecollection a
        inner join student b
        on a.student_id=b.id
        inner join batch c
        on b.class_id=c.id
        inner join course d
        on c.class_id=d.id
        where a.status=1 and a.transaction_status=1
        ) as x
        order by batch_name,full_name,admission_no,mo
        */
        
        if (request()->filled('start_date') && request()->filled('end_date')){
            $start_date = request('start_date');
            $end_date = request('end_date');
            $start = Carbon::createFromFormat('d/m/Y',$start_date)->format('Y-m-d');
            $end = Carbon::createFromFormat('d/m/Y',$end_date)->format('Y-m-d');

            session()->put('transport_paid',['start_date'=>$start,'end_date'=>$end]);
        }
        /*works while clicking page links*/
        else{
            $start = session('transport_paid.start_date');
            $end = session('transport_paid.end_date');
        }

        return Student::from('transport_feecollection as a')
                ->join('student as b','a.student_id','b.id')
                ->join('batch as c','b.class_id','c.id')
                ->join('course as d','c.class_id','d.id')
                ->where('a.status',1)
                ->where('a.transaction_status',1)
                ->wheredate('a.date','>=',$start)
                ->wheredate('a.date','<=',$end);
    }

    public function __construct() {
        // self::genQuery()->select('c.id')->dd();
        $ids = self::genQuery()->select('c.id')->pluck('c.id');
        session(['transport_paid.batchids'=>$ids]);
    }

    public function builder()
    {
        // batch_name,admission_no,full_name,month,amount

        $query = self::genQuery()
                    // ->select('b.admission_no','b.full_name','c.batch_name','a.date','a.month','a.amount')
                    // ->selectRaw("monthname(m.month)as unpaid")
                    ->orderby("d.id")
                    ->orderby("c.batch_name")
                    ->orderby("b.full_name")
                    ->orderby("a.date");
        
        // $query->dd();       
        return $query;
                    
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
                

            DateColumn::name('a.date')
                ->label('Paid Dt.')
                ->unsortable(),

            Column::raw('monthname(a.month)')
                ->label('Month')
                ->filterable()
                ->unsortable(),

            NumberColumn::name('a.amount')
                ->label('Amount')
                ->unsortable(),

            NumberColumn::name('a.discount_amount')
                ->label('Discount')
                ->unsortable(),

        ];
    }


    public function getBatchesProperty()
    {
        $ids = session('transport_paid.batchids');
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