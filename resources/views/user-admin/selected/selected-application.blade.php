@extends('layouts.admin')

@section('breadcrumbs')
<nav aria-label="Breadcrumb" class="mb-4 mt-2">
    <ol class="flex items-center gap-1 text-sm text-gray-700">
      <li>
        <a href="#" class="block transition-colors hover:text-gray-900"> Applications </a>
      </li>
  
      <li class="rtl:rotate-180">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="size-4"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"
          />
        </svg>
      </li>
  
      <li>
        <a href="/selected-applications" class="block transition-colors hover:text-gray-900"> Selected Applications </a>
      </li>
  
      <li class="rtl:rotate-180">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="size-4"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"
          />
        </svg>
      </li>
  
    </ol>
  </nav>
  
@endsection

@section('content')
    <div class="flex flex-col">
        <div class="text-start border-b border-[#1e1e1e]/10 pl-[14px] py-[10px]">
            <p class="text-[16px] md:text-[16px] font-bold">Selected Applications</p>
        </div>

        <div class="flex flex-col items-center flex-grow px-[14px] py-[10px] space-y-2">
            <div class="border border-[#1e1e1e]/15 self-start my-custom-search">
                <i class="fi fi-rs-search text-[#0f111c]"></i>
                <input type="search" name="" id="myCustomSearch" class="bg-transparent" placeholder="Search...">
            </div>

            <div class="w-full">
                <table id="selectedTable" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-[9px] px-4 py-2">
                              <span class="mr-2">LRN</span>
                              <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                              <span class="mr-2">Full Name</span>
                              <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                              <span class="mr-2">Age</span>
                              <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                              <span class="mr-2">Birthdate</span>
                              <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                              <span class="mr-2">Program</span>
                              <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                              <span class="mr-2">Grade Level</span>
                              <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                              <span class="mr-2">Created at</span>
                              <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-[9px] px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selected_applicants as $selected_applicant)
                          @if ($selected_applicant->interview->status == 'Scheduled' || $selected_applicant->interview->status == 'Pending')
                            <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $selected_applicant->applicationForm->lrn }}</td>
                                <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $selected_applicant->applicationForm->full_name }}</td>
                                <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $selected_applicant->applicationForm->age }}</td>
                                <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $selected_applicant->applicationForm->birthdate }}</td>
                                <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $selected_applicant->applicationForm->desired_program }}</td>
                                <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $selected_applicant->interview->status }}</td>
                                <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ \Carbon\Carbon::parse($selected_applicant->applicationForm->created_at)->timezone('Asia/Manila')->format('M. d - g:i A') }}</td>

                                <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate"><a href="/selected-application/interview-details/{{$selected_applicant->id }}">View</a></td>
                            </tr>
                          @endif
                        

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="module">
    let table;
    let pendingApplications = document.querySelector('#pending-application');

    document.addEventListener("DOMContentLoaded", function () {
        table = new DataTable('#selectedTable', {
            paging: true,
            pageLength: 20,
            searching: true,
            autoWidth: false,
            order: [[6, 'desc']],
            columnDefs: [
                { width: '16.66%', targets: '_all' }
            ],
            layout: {
              topStart: null,
              bottomStart: 'info',
              bottomEnd: 'paging',
            }
        });

        table.on('draw', function () {
            let newRow = document.querySelector('#myTable tbody tr:first-child');

            // Select all td elements within the new row
            let cells = newRow.querySelectorAll('td');

            cells.forEach(function(cell) {
                cell.classList.add(
                    'px-4',        // Horizontal padding
                    'py-2',        // Vertical padding
                    'text-start',  // Align text to the start (left)
                    'font-regular',
                    'text-[14px]',
                    'opacity-80',
                    'truncate'
                );
            });

        });

        //Overriding default search input
        const customSearch = document.getElementById("myCustomSearch");
        const defaultSearch = document.querySelector(".dt-search");

        defaultSearch.remove();
        customSearch.addEventListener("input", function(e) {
            table.search(this.value).draw();
        });


    });
</script>
@endpush
