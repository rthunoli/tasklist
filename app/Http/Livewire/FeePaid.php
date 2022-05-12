<?php

namespace App\Http\Livewire;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class FeePaid extends LivewireDatatable
{
    private static function genQuery()
    {
/*
select receipt_id,admission_no,full_name,batch_name as batch,
case method
when 1 then 'Cash'
when 4 then 'Online'
when 5 then 'Bank Through'
end as method,
amount,
case 
when discount is null then 0.00
else discount
end as discount,
receipt_date
from
(
select a.receipt_id,e.admission_no,e.full_name,f.batch_name,a.method,
sum(c.amount) as amount,sum(d.amount) as discount,a.receipt_date
from new_feereceipt a
inner join new_feereceipt_feecollection b
on a.id=b.feereceipt_id
inner join new_feecollection c
on c.id=b.feecollection_id
left join fee_discount d
on c.student_id=d.student_id
and c.batch_id=d.batch_id
and c.feegroup_id=d.feegroup_id
and c.feehead_id=d.feehead_id
and c.feetype_id=d.feetype_id
and c.feeinstallment_id=d.feeinstallment_id
and c.feeheadgroup_id=d.feeheadgroup_id
and c.status=d.status
inner join student e
on a.student_id=e.id
inner join batch f
on a.batch_id=f.id
where a.status=1 and c.status=1
group by a.receipt_id

union all

select a.receipt_id,e.admission_no,e.full_name,f.batch_name,a.method,
sum(c.amount) as amount,0 as discount,a.receipt_date
from admissionfee a
inner join adm_receipt_collection b
on a.id=b.admreceipt_id
inner join adm_feecollection c
on c.id=b.admcollection_id
inner join student e 
on a.student_id=e.id
inner join batch f
on a.batch_id=f.id
where a.status=4 and c.status=1
group by a.receipt_id
) as x
order by 1
*/
        
        if (request()->filled('start_date') && request()->filled('end_date')){
            $start_date = request('start_date');
            $end_date = request('end_date');
            $start = Carbon::createFromFormat('d/m/Y',$start_date)->format('Y-m-d');
            $end = Carbon::createFromFormat('d/m/Y',$end_date)->format('Y-m-d');

            session()->put('fee_paid',['start_date'=>$start,'end_date'=>$end]);
        }
        /*works while clicking page links*/
        else{
            $start = session('fee_paid.start_date');
            $end = session('fee_paid.end_date');
        }

        $aca_fee = Student::select('a.receipt_id','a.receipt_date','e.admission_no','e.full_name',
            'f.batch_name',
            DB::raw("case a.method when 1 then 'Cash' when 4 then 'Online' when 5 then 'Bank Through' end as method"),
            DB::raw('sum(c.amount) as amount'),
            DB::raw('sum(d.amount) as discount'),
            DB::raw("'Academic' as fee_type"))
            ->from('new_feereceipt as a')
            ->join('new_feereceipt_feecollection as b','a.id','b.feereceipt_id')
            ->join('new_feecollection as c','c.id','b.feecollection_id')
            ->leftjoin('fee_discount as d',function($join){
                $join->on([['c.student_id','d.student_id'],
                    ['c.batch_id','d.batch_id'],
                    ['c.feegroup_id','d.feegroup_id'],
                    ['c.feehead_id','d.feehead_id'],
                    ['c.feetype_id','d.feetype_id'],
                    ['c.feeinstallment_id','d.feeinstallment_id'],
                    ['c.feeheadgroup_id','d.feeheadgroup_id'],
                    ['c.status','d.status']
                ]);
            })
            ->join('student as e','a.student_id','e.id')
            ->join('batch as f','a.batch_id','f.id')
            ->where([['a.status',1],['c.status',1]])
            ->wheredate('a.receipt_date','>=',$start)
            ->wheredate('a.receipt_date','<=',$end)
            ->groupby(['a.receipt_id','a.receipt_date','e.admission_no','e.full_name','f.batch_name','a.method']);

     
        
        $adm_fee = Student::select('a.receipt_id','a.receipt_date','e.admission_no','e.full_name',
            'f.batch_name',
            DB::raw("case a.method when 1 then 'Cash' when 4 then 'Online' when 5 then 'Bank Through' end as method"),
            DB::raw('sum(c.amount) as amount'),
            DB::raw('null as discount'),
            DB::raw("'Admission' as fee_type"))
            ->from('admissionfee as a')
            ->join('adm_receipt_collection as b','a.id','b.admreceipt_id')
            ->join('adm_feecollection as c','c.id','b.admcollection_id')
            ->join('student as e','a.student_id','e.id')
            ->join('batch as f','a.batch_id','f.id')
            ->where([['a.status',4],['c.status',1]])
            ->wheredate('a.receipt_date','>=',$start)
            ->wheredate('a.receipt_date','<=',$end)
            ->groupby(['a.receipt_id','a.receipt_date','e.admission_no','e.full_name','f.batch_name','a.method']);

        // $aca_fee->dd();
        // return $aca_fee;

        // $adm_fee->dd();
        // return $adm_fee;

        $query = Student::from($aca_fee->unionall($adm_fee),'x');

        // $query->select('*')->from($query,'x');
        // $query->dd();

        return $query;
    }
    
    // public function __construct() {
    //     $ids = self::genQuery()->select('c.id')->pluck('c.id');
    //     session(['transport_defaulters.batchids'=>$ids]);
    // }

    public function builder()
    {
        // dd('builder');
        return self::genQuery();
    }

    /*
    a.receipt_id,a.receipt_date,e.admission_no,e.full_name,f.batch_name,a.method,
    sum(c.amount) as amount,0 as discount
    */

    public function columns()
    {
        return [

            Column::name('x.receipt_id')
                ->label('Rept No.')
                ->searchable(),
            
            DateColumn::name('x.receipt_date')
                ->label('Rcpt. Date')
                ->sortable(),
            
            Column::name('x.admission_no')
                ->label('Adm. No.')
                ->searchable(),
            
            Column::name('x.full_name')
                ->label('Name')
                ->searchable(),
                
            Column::name('x.batch_name')
                ->label('Batch')
                ->filterable(),
            
            Column::name('x.method')
                ->label('Method')
                ->filterable(),
            
            NumberColumn::name('x.amount')
                ->label('Amount'),
            
            NumberColumn::name('x.discount')
                ->label('Discount'),

            Column::name('x.fee_type')
                ->label('Fee Type')
                ->filterable(),
            
        ];
    }
}