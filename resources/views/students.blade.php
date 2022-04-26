<x-app-layout>
    <x-slot name="title">
        {{ config('app.name').' - Search' }} 
    </x-slot>
    <div class="py-2">
        {{-- max-w-7xl sm:px-6 lg:px-8 --}}
        <div class="mx-auto sm:px-1 lg:px-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-2xl font-sans text-blue-700 w-fit p-1 mb-4 shadow-md">
                        @if(request()->filled('search'))
                            Students | {{ request()->search }}
                        @else
                            Students
                        @endif
                    </div>
                    <div class="border sm:rounded-lg overflow-x-auto">
                        <table class="table table-auto whitespace-nowrap">
                            <thead>
                                <tr>
                                    {{-- Details from student table --}}
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Adm. No</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Name</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Status</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Gender</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Dob</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Phone</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Phone Home</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Adm. Dt</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Adm. Class</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Sec. Lang</th>

                                    {{-- Details from guardian table --}}
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Father Name</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Father Occup.</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Father Phone</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Father Res. Addr.</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Mother Name</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Mother Occup.</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Mother Phone</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Mother Res. Addr.</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Guardian Name</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Guardian Phone</th>
                                    <th class="px-1 py-2 border-b-2 border-r border-r-gray-300 bg-gray-200">Guardian Addr.</th>
                                </tr>
                            <thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    {{-- Details from student table --}}
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->admission_no }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->full_name }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->status }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->gender }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->dob }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->phone_no }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->phone_home }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->admission_date }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->admitted_class }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->second_language }}</td>
                                    
                                    {{-- Details from guardian table --}}
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->father_name }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->father_occupation }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->phone_father }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->father_residential_address }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->mother_name }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->mother_occupation }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->phone_mother }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->mother_residential_address }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->guardian_name }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->phone_guardian }}</td>
                                    <td class="px-1 py-1 border-b-2 border-r">{{ $student->guardian_address }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $students->links() }}                   
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>