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
        <a href="/pending-applications" class="block transition-colors hover:text-gray-900"> Pending Applications </a>
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

@section('header')


@endsection

@section('content')

  @foreach ($pending_applicants as $pending_applicant)
  
    <p>{{ $pending_applicant->applicationForm->lrn }}</p>
    <p>{{ $pending_applicant->applicationForm->full_name }}</p>

  @endforeach

@endsection
