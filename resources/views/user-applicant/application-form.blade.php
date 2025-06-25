<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body>
    <h1>Admission form</h1>


    <form action="/admission/application-form" method="POST" class="flex flex-col">

        @csrf

        <label for="LRN">LRN</label>
        <input type="number" name="lrn" id="LRN">

        @error('lrn')
            {{ $message }}
        @enderror

        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" id="full_name">

        @error('full_name')
            {{ $message }}
        @enderror

        <label for="age">Age</label>
        <input type="number" name="age" id="age">

        @error('age')
            {{ $message }}
        @enderror

        <label for="birthdate">Birthdate</label>
        <input type="date" name="birthdate" id="birthdate">

        @error('birthdate')
            {{ $message }}
        @enderror

        <label for="desired_program">Program Selection</label>
        <select name="desired_program" id="desired_program">
            <option>Select your desired program</option>
            <option value="HUMSS">HUMSS</option>
            <option value="ABM">ABM</option>
        </select>

        @error('desired_program')
            {{ $message }}
        @enderror

        <label for="grade_level">Grade Level Selection</label>
        <select name="grade_level" id="grade_level">
            <option>Select your grade level</option>
            <option value="Grade 11">Grade 11</option>
            <option value="Grade 12">Grade 12</option>
        </select>

        @error('grade_level')
            <p class="text-red-500">{{ $message }}</p>
        @enderror

        <button>Submit</button>


    </form>
    
    @if ($errors->any())
        <div class="text-red-500 text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

</body>

</html>
