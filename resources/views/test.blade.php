@extends('layouts.app')

@section('content')
    <h2>askdlasjdklasjdklasjdklasjdklsa</h2>
    {{-- <form action="/test/1" method="post">

        @csrf

        <button>submit</button>

    </form> --}}
    <form action="/students/import" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
        <button type="submit">Upload</button>
    </form>

    @error('message')
        {{ $message }}
    @enderror

@endsection
