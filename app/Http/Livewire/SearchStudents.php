<?php

namespace App\Http\Livewire;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\StudentStatus;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class SearchStudents extends LivewireDatatable
{
   
    public function builder(){
        $query = Student::from('student as a')
                ->join('guardian as b','a.parent_id','b.id')
                ->join('app_db.student_status as c','a.status','c.id')
                ->join('batch as d','a.class_id','d.id');
                // ->select('a.admission_no','a.full_name','a.gender','a.phone_no','a.phone_home',
                //     'a.admission_date','a.admitted_class','a.second_language','b.father_name',
                //     'b.father_occupation','b.phone_father','b.father_residential_address',
                //     'b.mother_name','b.mother_occupation','b.phone_mother','b.mother_residential_address',
                //     'b.guardian_name','b.phone_guardian','b.guardian_address');
    
        return $query;
    }
    
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [

            Column::callback('a.id,a.admission_no',function($id,$admno){
                    $subdomain = Str::lower(session('db'));
                    $url = "https://$subdomain.kadamburschool.net/students/admin/view/${id}";
                    
                    return view('livewire.link', [
                        'target' => '_blank',
                        'href' => $url,
                        'slot' => $admno
                        ]);
                })
                ->exportCallback(function($id,$admno){
                    return $admno;
                })
                ->unwrap()
                ->label('Adm. No')
                ->searchable(),
              
            Column::name('a.full_name')
                ->label('Full Name')
                ->searchable(),
            
            Column::name('d.batch_name')
                ->label('Current Batch')
                ->filterable(),
            
            Column::name('c.status')
                ->label('Status')
                ->filterable($this->StudentStatus),

            Column::name('a.gender')
                ->label('Gender')
                ->filterable(),
            
            Column::name('a.phone_no')
                ->label('Contact Phone')
                ->searchable(),

            Column::name('a.phone_home')
                ->label('Home Phone')
                ->searchable(),
            
            Column::name('b.phone_father')
                ->label('Father\'s Phone')
                ->searchable(),

            DateColumn::name('a.admission_date')
                ->label('Adm. Date')
                ->sortable()
                ->filterable(),
            
            DateColumn::name('a.dob')
                ->label('Dob'),

            // Column::name('a.second_language')
            //     ->label('Sec. Lang.'),

            Column::name('b.father_name')
                ->label('Name of Father')
                ->searchable(),

            Column::name('b.father_occupation')
                ->label('Occup. of Father')
                ->searchable(),

            // Column::name('b.father_residential_address')
            //     ->label('Father Res. Addr')
            //     ->searchable(),,

            Column::name('b.mother_name')
                ->label('Name of Mother')
                ->searchable(),

            Column::name('a.admitted_class')
                ->label('Adm. Class'),

            Column::name('b.mother_occupation')
                ->label('Occup. of Mother')
                ->searchable(),

            Column::name('b.phone_mother')
                ->label('Mother\'s Phone')
                ->searchable(),

            // Column::name('b.mother_residential_address')
            //     ->label('Mother Res. Addr')
            //     ->searchable(),

            Column::name('b.guardian_name')
                ->label('Name of Guardian')
                ->searchable(),

            Column::name('b.phone_guardian')
                ->label('Guardian\'s Phone')
                ->searchable(),

            // Column::name('b.guardian_address')
            //     ->label('Guardian Addr')
            //     ->searchable(),

        ];
    }

    public function getStudentStatusProperty()
    {
        return StudentStatus::pluck('status');
    }

}

