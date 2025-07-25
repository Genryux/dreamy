<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body>
    <h1>Admission form</h1>


    <form action="/admission/application-form" method="POST" class="flex flex-col">

        @csrf

        <input type="text" name="preferred_sched" id="preferred_sched" placeholder="preferred_sched">

        <div>
            <p>is returning</p>
            <label for="true">Yes</label>
            <input type="radio" name="is_returning" id="true" value="1" placeholder="is_returning">
            <label for="false">No</label>
            <input type="radio" name="is_returning" id="false" value="0" placeholder="is_returning">
        </div>

        <input type="number" maxlength="12" minlength="12" pattern="\d{12}" name="lrn" id="lrn" placeholder="lrn">
        <input type="text" name="grade_level" id="grade_level" placeholder="grade_level">
        <input type="text" name="primary_track" id="primary_track" placeholder="primary_track">
        <input type="text" name="secondary_track" id="secondary_track" placeholder="secondary_track">

        <input type="text" name="first_name" id="first_name" placeholder="first_name">
        <input type="text" name="last_name" id="last_name" placeholder="last_name">
        <input type="text" name="last_name" id="last_name" placeholder="last_name">
        <input type="text" name="middle_name" id="middle_name" placeholder="middle_name">
        <input type="text" name="extension_name" id="extension_name" placeholder="extension_name">
        <input type="date" name="birthdate" id="birthdate" placeholder="birthdate">
        <input type="number" name="age" id="age" placeholder="age">
        <input type="text" name="place_of_birth" id="place_of_birth" placeholder="place_of_birth">
        <input type="text" name="mother_tongue" id="mother_tongue" placeholder="mother_tongue">
        <input type="email" name="email" id="email" placeholder="email">

        <div>
            <p>is 4ps</p>
            <label for="true">Yes</label>
            <input type="radio" name="is_4ps_beneficiary" id="true" value="1"
                placeholder="is_4ps_beneficiary">
            <label for="false">No</label>
            <input type="radio" name="is_4ps_beneficiary" id="false" value="0"
                placeholder="is_4ps_beneficiary">
        </div>
        <div>
            <p>belongs_to_ip</p>
            <label for="true">Yes</label>
            <input type="radio" name="belongs_to_ip" id="true" value="1" placeholder="belongs_to_ip">
            <label for="false">No</label>
            <input type="radio" name="belongs_to_ip" id="false" value="0" placeholder="belongs_to_ip">
        </div>

        <input type="text" name="cur_house_no" id="cur_house_no" placeholder="cur_house_no">
        <input type="text" name="cur_street" id="cur_street" placeholder="cur_street">
        <input type="text" name="cur_barangay" id="cur_barangay" placeholder="cur_barangay">
        <input type="text" name="cur_city" id="cur_city" placeholder="cur_city">
        <input type="text" name="cur_province" id="cur_province" placeholder="cur_province">
        <input type="text" name="cur_country" id="cur_country" placeholder="cur_country">
        <input type="text" name="cur_zip_code" id="cur_zip_code" placeholder="cur_zip_code">
        <input type="text" name="perm_house_no" id="perm_house_no" placeholder="perm_house_no">
        <input type="text" name="perm_street" id="perm_street" placeholder="perm_street">
        <input type="text" name="perm_barangay" id="perm_barangay" placeholder="perm_barangay">
        <input type="text" name="perm_city" id="perm_city" placeholder="perm_city">
        <input type="text" name="perm_province" id="perm_province" placeholder="perm_province">
        <input type="text" name="perm_country" id="perm_country" placeholder="perm_country">
        <input type="text" name="perm_zip_code" id="perm_zip_code" placeholder="perm_zip_code">

        <input type="text" name="father_last_name" id="father_last_name" placeholder="father_last_name">
        <input type="text" name="father_first_name" id="father_first_name" placeholder="father_first_name">
        <input type="text" name="father_middle_name" id="father_middle_name" placeholder="father_middle_name">
        <input type="number" name="father_contact_number" id="father_contact_number"
            placeholder="father_contact_number">
        <input type="text" name="mother_last_name" id="mother_last_name" placeholder="mother_last_name">
        <input type="text" name="mother_first_name" id="mother_first_name" placeholder="mother_first_name">
        <input type="text" name="mother_middle_name" id="mother_middle_name" placeholder="mother_middle_name">
        <input type="number" name="mother_contact_number" id="mother_contact_number"
            placeholder="mother_contact_number">
        <input type="text" name="guardian_last_name" id="guardian_last_name" placeholder="guardian_last_name">
        <input type="text" name="guardian_first_name" id="guardian_first_name" placeholder="guardian_first_name">
        <input type="text" name="guardian_middle_name" id="guardian_middle_name"
            placeholder="guardian_middle_name">
        <input type="number" name="guardian_contact_number" id="guardian_contact_number"
            placeholder="guardian_contact_number">

        <div>
            <p>has_special_needs</p>
            <label for="true">Yes</label>
            <input type="radio" name="has_special_needs" id="true" value="1"
                placeholder="has_special_needs">
            <label for="false">No</label>
            <input type="radio" name="has_special_needs" id="false" value="0"
                placeholder="has_special_needs">
        </div>

        <div>
            <p>special_needs</p>
            <label for="special_needs1">special_needs1</label>
            <input type="checkbox" name="special_needs[]" id="special_needs1" value="special_needs1" placeholder="special_needs">
            <label for="special_needs2">special_needs2</label>
            <input type="checkbox" name="special_needs[]" id="special_needs2" value="special_needs2" placeholder="special_needs">
            <label for="special_needs3">special_needs3</label>
            <input type="checkbox" name="special_needs[]" id="special_needs3" value="special_needs3" placeholder="special_needs">
            <label for="special_needs4">special_needs4</label>
            <input type="checkbox" name="special_needs[]" id="special_needs4" value="special_needs4" placeholder="special_needs">
        </div>

        <input type="number" name="last_grade_level_completed" id="last_grade_level_completed"
            placeholder="last_grade_level_completed">
        <input type="text" name="last_school_attended" id="last_school_attended"
            placeholder="last_school_attended">
        <input type="date" name="last_school_year_completed" id="last_school_year_completed"
            placeholder="last_school_year_completed">
        <input type="text" name="school_id" id="school_id" placeholder="school_id">

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
