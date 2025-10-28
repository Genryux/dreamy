<!DOCTYPE html>
<html lang="en">
<x-head :title="$title ?? 'Application Form'"></x-head>

<body class="bg-[#1A3165] min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-1.5">
            <!-- Form Container -->

            <div class="flex">
                <!-- First container: right edge slants backward "\" -->
                <div
                    class="w-[75%] rounded-tl-xl h-30 bg-gradient-to-r from-[#199BCF] to-[#0D4F69] border border-[#199BCF] text-white flex items-center justify-center [clip-path:polygon(0_0,100%_0,99%_100%,0_100%)] pt-4">
                    <div class="pt-2">
                        <h1 class="text-[24px] md:text-[46px] font-bold text-white text-center leading-none">ENROLLMENT FORM</h1>
                        <p class="text-blue-100 text-[14px] md:text-[16px] text-center mt-2">Please fill out all required information accurately
                        </p>
                        <div id="auto-save-indicator"
                            class="text-blue-200 text-sm text-center mt-2 opacity-0 transition-opacity duration-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Auto-saved
                        </div>
                    </div>
                </div>

                <!-- Second container: left edge slants forward "/" -->
                <div
                    class="w-[25%] h-30 rounded-tr-xl bg-[#0D4F69] border border-[#199BCF] text-white flex items-center justify-center [clip-path:polygon(4%_0,100%_0,100%_100%,0_100%)] p-4">
                    <img src="{{ asset('images/Dreamy_logo.png') }}" alt="dreamy_logo" class="h-[100px] md:h-[130px]">

                </div>
            </div>

            <div class="bg-white rounded-bl-xl rounded-br-xl shadow-xl overflow-hidden">

                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex " aria-label="Tabs">
                        <button
                            class="flex-1 tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                            data-tab="0">
                            Personal Info
                        </button>
                        <button
                            class="flex-1 tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                            data-tab="1">
                            Academic Info
                        </button>
                        <button
                            class="flex-1 tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                            data-tab="2">
                            Contact Details
                        </button>
                        <button
                            class="flex-1 tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                            data-tab="3">
                            Family Info
                        </button>
                        <button
                            class="flex-1 tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                            data-tab="4">
                            Review
                        </button>
                    </nav>
                </div>

                <!-- Form Content -->
                <form id="applicationForm" action="/admission/application-form" method="POST" class="p-8">
                    <!-- Use the meta tag token directly to ensure consistency -->
                    <input type="hidden" name="_token" id="form-csrf-token" value="">
                    
                    <!-- CSRF Token Synchronization -->
                    <script>
                        // Synchronize form token with meta tag token
                        const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const formTokenInput = document.getElementById('form-csrf-token');
                        if (formTokenInput && metaToken) {
                            formTokenInput.value = metaToken;
                        }
                    </script>

                    <!-- Hidden fields for user data -->
                    <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                    <input type="hidden" name="last_name" value="{{ $user->last_name }}">

                    <!-- Personal Info Tab -->
                    <div id="personal-info" class="tab-content">
                        <div class="mb-6">
                            <h2 class="text-[24px] md:text-[20px] font-bold text-gray-700 mb-2">Personal Information
                            </h2>
                            <p class="text-[16px] md:text-[14px] text-gray-500">Please provide your basic personal
                                details</p>
                        </div>

                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-500 mb-1">First Name
                                    *</label>
                                <input type="text" name="first_name" id="first_name" value="{{ $user->first_name }}"
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="Enter first name" readonly>
                            </div>
                            <div>
                                <label for="middle_name" class="block text-sm font-medium text-gray-500 mb-1">Middle
                                    Name</label>
                                <input type="text" name="middle_name" id="middle_name"
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="Enter middle name" value="{{ old('middle_name') }}">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-500 mb-1">Last Name
                                    *</label>
                                <input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}"
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="Enter last name" readonly>
                            </div>
                        </div>

                        <!-- Extension and Birth Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="extension_name"
                                    class="block text-sm font-medium text-gray-500 mb-1">Extension Name</label>
                                <input type="text" name="extension_name" id="extension_name"
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="Enter extension name" value="{{ old('extension_name') }}">
                            </div>
                            <div>
                                <label for="birthdate" class="block text-sm font-medium text-gray-500 mb-1">Date of
                                    Birth *</label>
                                <input type="date" name="birthdate" id="birthdate" required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    value="{{ old('birthdate') }}">
                            </div>
                        </div>

                        <!-- Age and Gender -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="age" class="block text-sm font-medium text-gray-500 mb-1">Age *</label>
                                <input type="number" name="age" id="age" required min="1"
                                    max="100"
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="Enter age" value="{{ old('age') }}">
                            </div>
                            <div id="gender-group">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Gender *</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="gender" value="male"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-500">Male</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="gender" value="female"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-500">Female</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Place of Birth and Mother Tongue -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="place_of_birth" class="block text-sm font-medium text-gray-500 mb-1">Place
                                    of Birth *</label>
                                <input type="text" name="place_of_birth" id="place_of_birth" required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="Enter place of birth">
                            </div>
                            <div>
                                <label for="mother_tongue" class="block text-sm font-medium text-gray-500 mb-1">Mother
                                    Tongue *</label>
                                <input type="text" name="mother_tongue" id="mother_tongue" required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="Enter mother tongue">
                            </div>
                        </div>

                        <!-- Email and Contact -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-500 mb-1">Email Address
                                *</label>
                            <input type="email" name="email" id="email" required
                                class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                placeholder="Enter email address">
                            <p class="text-sm text-gray-500 mt-1">We'll use this for important notifications</p>
                        </div>

                        <div class="mb-6">
                            <label for="contact_number" class="block text-sm font-medium text-gray-500 mb-1">Contact
                                Number *</label>
                            <input type="tel" name="contact_number" id="contact_number" required
                                class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                placeholder="09XX-XXXX-XXXX">
                        </div>

                        <!-- 4Ps and IP Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div id="fourps-group">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Is this student a 4Ps
                                    beneficiary? *</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="is_4ps_beneficiary" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-500">Yes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="is_4ps_beneficiary" value="0"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-500">No</span>
                                    </label>
                                </div>
                            </div>
                            <div id="ip-group">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Does the student belong to
                                    Indigenous People (IP)? *</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="belongs_to_ip" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-500">Yes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="belongs_to_ip" value="0"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-500">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Info Tab -->
                    <div id="academic-info" class="tab-content hidden">
                        <div class="mb-6">
                            <h2 class="text-[24px] md:text-[20px] font-bold text-gray-700 mb-2">Academic Information
                            </h2>
                            <p class="text-[16px] md:text-[14px] text-gray-500">Select your preferred track and provide
                                academic details</p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Note:</strong> Your track and strand selection will determine your
                                        curriculum and subject offerings.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="grade_level" class="block text-sm font-medium text-gray-500 mb-1">Grade
                                    Level *</label>
                                <select name="grade_level" id="grade_level" required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200">
                                    <option value="">Select grade level</option>
                                    <option value="Grade 11">Grade 11</option>
                                    <option value="Grade 12">Grade 12</option>
                                </select>
                            </div>
                            <div>
                                <label for="preferred_sched"
                                    class="block text-sm font-medium text-gray-500 mb-1">Preferred Schedule *</label>
                                <select name="preferred_sched" id="preferred_sched" required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200">
                                    <option value="">Select schedule</option>
                                    <option value="Morning">Morning (7:00 AM - 12:00 PM)</option>
                                    <option value="Afternoon">Afternoon (12:00 PM - 5:00 PM)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="primary_track"
                                    class="block text-sm font-medium text-gray-500 mb-1">Primary Track *</label>
                                <select name="primary_track" id="primary_track" required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200">
                                    <option value="">Select primary track</option>
                                    @foreach ($tracks as $track)
                                        <option value="{{ $track->id }}">{{ $track->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="secondary_track"
                                    class="block text-sm font-medium text-gray-500 mb-1">Secondary Track/Strand
                                    *</label>
                                <select name="secondary_track" id="secondary_track" required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200">
                                    <option value="">Select strand</option>
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}" data-track="{{ $program->track_id }}">
                                            {{ $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Returning/Transferring Status -->
                        <div id="returning-group" class="mb-6">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Is this student
                                returning/transferring? *</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="is_returning" value="1"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-500">Yes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_returning" value="0"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-500">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Previous School Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-[20px] md:text-[18px] font-medium text-gray-700 mb-4">Previous School
                                Information (if
                                applicable)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="last_grade_level_completed"
                                        class="block text-sm font-medium text-gray-500 mb-1">Last Grade Level
                                        Completed</label>
                                    <input type="text" name="last_grade_level_completed"
                                        id="last_grade_level_completed"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter last grade level">
                                </div>
                                <div>
                                    <label for="last_school_attended"
                                        class="block text-sm font-medium text-gray-500 mb-1">Last School
                                        Attended</label>
                                    <input type="text" name="last_school_attended" id="last_school_attended"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter school name">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="last_school_year_completed"
                                        class="block text-sm font-medium text-gray-500 mb-1">Last School Year
                                        Completed</label>
                                    <input type="date" name="last_school_year_completed"
                                        id="last_school_year_completed"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200">
                                </div>
                                <div>
                                    <label for="lrn" class="block text-sm font-medium text-gray-500 mb-1">LRN
                                        (Learner Reference Number)</label>
                                    <input type="text" name="lrn" id="lrn" maxlength="12"
                                        pattern="\d{12}"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter 12-digit LRN" value="{{ old('lrn') }}">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="school_id" class="block text-sm font-medium text-gray-500 mb-1">School
                                        ID</label>
                                    <input type="text" name="school_id" id="school_id"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter school ID (if applicable)" value="{{ old('school_id') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details Tab -->
                    <div id="contact-details" class="tab-content hidden">
                        <div class="mb-6">
                            <h2 class="text-[24px] md:text-[20px] font-bold text-gray-700 mb-2">Contact Details</h2>
                            <p class="text-[16px] md:text-[14px] text-gray-500">Provide your current and permanent
                                addresses</p>
                        </div>

                        <!-- Current Address -->
                        <div class="mb-8">
                            <h3 class="text-[20px] md:text-[18px] font-medium text-gray-700 mb-4">Current Address</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="cur_house_no"
                                        class="block text-sm font-medium text-gray-500 mb-1">House No. *</label>
                                    <input type="text" name="cur_house_no" id="cur_house_no" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter house number" value="{{ old('cur_house_no') }}">
                                </div>
                                <div>
                                    <label for="cur_street"
                                        class="block text-sm font-medium text-gray-500 mb-1">Street</label>
                                    <input type="text" name="cur_street" id="cur_street"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter street name" value="{{ old('cur_street') }}">
                                </div>
                                <div>
                                    <label for="cur_barangay"
                                        class="block text-sm font-medium text-gray-500 mb-1">Barangay *</label>
                                    <input type="text" name="cur_barangay" id="cur_barangay" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter barangay" value="{{ old('cur_barangay') }}">
                                </div>
                                <div>
                                    <label for="cur_city"
                                        class="block text-sm font-medium text-gray-500 mb-1">City/Municipality
                                        *</label>
                                    <input type="text" name="cur_city" id="cur_city" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter city or municipality" value="{{ old('cur_city') }}">
                                </div>
                                <div>
                                    <label for="cur_province"
                                        class="block text-sm font-medium text-gray-500 mb-1">Province *</label>
                                    <input type="text" name="cur_province" id="cur_province" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter province" value="{{ old('cur_province') }}">
                                </div>
                                <div>
                                    <label for="cur_country"
                                        class="block text-sm font-medium text-gray-500 mb-1">Country *</label>
                                    <input type="text" name="cur_country" id="cur_country" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter country" value="{{ old('cur_country', 'Philippines') }}">
                                </div>
                                <div>
                                    <label for="cur_zip_code" class="block text-sm font-medium text-gray-500 mb-1">Zip
                                        Code *</label>
                                    <input type="text" name="cur_zip_code" id="cur_zip_code" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter zip code" value="{{ old('cur_zip_code') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Permanent Address -->
                        <div>
                            <h3 class="text-[20px] md:text-[18px] font-medium text-gray-700 mb-4">Permanent Address
                            </h3>
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="sameAsCurrent" name="sameAsCurrent"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-500">Same as current address</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="perm_house_no"
                                        class="block text-sm font-medium text-gray-500 mb-1">House No. *</label>
                                    <input type="text" name="perm_house_no" id="perm_house_no" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter house number" value="{{ old('perm_house_no') }}">
                                </div>
                                <div>
                                    <label for="perm_street"
                                        class="block text-sm font-medium text-gray-500 mb-1">Street</label>
                                    <input type="text" name="perm_street" id="perm_street"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter street name" value="{{ old('perm_street') }}">
                                </div>
                                <div>
                                    <label for="perm_barangay"
                                        class="block text-sm font-medium text-gray-500 mb-1">Barangay *</label>
                                    <input type="text" name="perm_barangay" id="perm_barangay" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter barangay" value="{{ old('perm_barangay') }}">
                                </div>
                                <div>
                                    <label for="perm_city"
                                        class="block text-sm font-medium text-gray-500 mb-1">City/Municipality
                                        *</label>
                                    <input type="text" name="perm_city" id="perm_city" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter city or municipality" value="{{ old('perm_city') }}">
                                </div>
                                <div>
                                    <label for="perm_province"
                                        class="block text-sm font-medium text-gray-500 mb-1">Province *</label>
                                    <input type="text" name="perm_province" id="perm_province" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter province" value="{{ old('perm_province') }}">
                                </div>
                                <div>
                                    <label for="perm_country"
                                        class="block text-sm font-medium text-gray-500 mb-1">Country *</label>
                                    <input type="text" name="perm_country" id="perm_country" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter country" value="{{ old('perm_country', 'Philippines') }}">
                                </div>
                                <div>
                                    <label for="perm_zip_code"
                                        class="block text-sm font-medium text-gray-500 mb-1">Zip Code *</label>
                                    <input type="text" name="perm_zip_code" id="perm_zip_code" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter zip code" value="{{ old('perm_zip_code') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Family Info Tab -->
                    <div id="family-info" class="tab-content hidden">
                        <div class="mb-6">
                            <h2 class="text-[24px] md:text-[20px] font-bold text-gray-700 mb-2">Family Information</h2>
                            <p class="text-[16px] md:text-[14px] text-gray-500">Please provide parent/guardian details
                            </p>
                        </div>

                        <!-- Father's Information -->
                        <div class="mb-8">
                            <h3 class="text-[20px] md:text-[18px] font-medium text-gray-700 mb-4">Father's Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="father_last_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">Last Name *</label>
                                    <input type="text" name="father_last_name" id="father_last_name" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter last name">
                                </div>
                                <div>
                                    <label for="father_first_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">First Name *</label>
                                    <input type="text" name="father_first_name" id="father_first_name" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter first name">
                                </div>
                                <div>
                                    <label for="father_middle_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">Middle Name</label>
                                    <input type="text" name="father_middle_name" id="father_middle_name"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter middle name">
                                </div>
                            </div>
                            <div>
                                <label for="father_contact_number"
                                    class="block text-sm font-medium text-gray-500 mb-1">Contact Number *</label>
                                <input type="tel" name="father_contact_number" id="father_contact_number"
                                    required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="09XX-XXX-XXXX">
                            </div>
                        </div>

                        <!-- Mother's Information -->
                        <div class="mb-8">
                            <h3 class="text-[20px] md:text-[18px] font-medium text-gray-700 mb-4">Mother's Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="mother_last_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">Last Name *</label>
                                    <input type="text" name="mother_last_name" id="mother_last_name" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter last name">
                                </div>
                                <div>
                                    <label for="mother_first_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">First Name *</label>
                                    <input type="text" name="mother_first_name" id="mother_first_name" required
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter first name">
                                </div>
                                <div>
                                    <label for="mother_middle_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">Middle Name</label>
                                    <input type="text" name="mother_middle_name" id="mother_middle_name"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter middle name">
                                </div>
                            </div>
                            <div>
                                <label for="mother_contact_number"
                                    class="block text-sm font-medium text-gray-500 mb-1">Contact Number *</label>
                                <input type="tel" name="mother_contact_number" id="mother_contact_number"
                                    required
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="09XX-XXX-XXXX">
                            </div>
                        </div>

                        <!-- Guardian's Information -->
                        <div class="mb-8">
                            <h3 class="text-[20px] md:text-[18px] font-medium text-gray-700 mb-4">Guardian's
                                Information (if applicable)
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="guardian_last_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">Last Name</label>
                                    <input type="text" name="guardian_last_name" id="guardian_last_name"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter last name">
                                </div>
                                <div>
                                    <label for="guardian_first_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">First Name</label>
                                    <input type="text" name="guardian_first_name" id="guardian_first_name"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter first name">
                                </div>
                                <div>
                                    <label for="guardian_middle_name"
                                        class="block text-sm font-medium text-gray-500 mb-1">Middle Name</label>
                                    <input type="text" name="guardian_middle_name" id="guardian_middle_name"
                                        class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                        placeholder="Enter middle name">
                                </div>
                            </div>
                            <div>
                                <label for="guardian_contact_number"
                                    class="block text-sm font-medium text-gray-500 mb-1">Contact Number</label>
                                <input type="tel" name="guardian_contact_number" id="guardian_contact_number"
                                    class="w-full px-3 py-3 border-2 border-gray-300 rounded-md bg-gray-50 shadow-sm outline-none focus:border-[#199BCF]/70 focus:ring focus:ring-[#199BCF]/20 md:hover:ring md:hover:ring-[#199BCF]/20 md:focus-within:ring-[#199BCF]/20 md:py-2 md:text-[14px] transition duration-200"
                                    placeholder="09XX-XXX-XXXX">
                            </div>
                        </div>

                        <!-- Special Needs -->
                        <div>
                            <h3 class="text-[20px] md:text-[18px] font-medium text-gray-700 mb-4">Special Needs
                                (Optional)</h3>
                            <div id="special-needs-group" class="mb-4">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Does the student have
                                    special needs?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="has_special_needs" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-500">Yes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="has_special_needs" value="0"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-500">No</span>
                                    </label>
                                </div>
                            </div>
                            <div id="special-needs-checkboxes" class="hidden">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Please specify special
                                    needs:</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="special_needs[]" value="Visual Impairment"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-500">Visual Impairment</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="special_needs[]" value="Hearing Impairment"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-500">Hearing Impairment</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="special_needs[]" value="Learning Disability"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-500">Learning Disability</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="special_needs[]" value="Physical Disability"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-500">Physical Disability</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="special_needs[]"
                                            value="Autism Spectrum Disorder"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-500">Autism Spectrum Disorder</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="special_needs[]" value="Other"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-500">Other</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Tab -->
                    <div id="review" class="tab-content hidden">
                        <div class="mb-6">
                            <h2 class="text-[24px] md:text-[20px] font-bold text-gray-700 mb-2">Review & Submit</h2>
                            <p class="text-[16px] md:text-[14px] text-gray-500">Please review all information before
                                submitting</p>
                        </div>

                        <!-- Important Notice -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Important:</strong> Please ensure all information is accurate. Once
                                        submitted, you may need to contact the admissions office to make changes.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Review Sections -->
                        <div class="space-y-6">
                            <!-- Personal Information Review -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Personal Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-full-name" class="text-[14px]">[To be populated from form
                                                data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of
                                            Birth</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-birthdate" class="text-[14px]">[To be populated from form
                                                data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-age" class="text-[14px]">[To be populated from form
                                                data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-gender" class="text-[14px]">[To be populated from form
                                                data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Place of
                                            Birth</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-place-of-birth" class="text-[14px]">[To be populated from
                                                form data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email
                                            Address</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-email" class="text-[14px]">[To be populated from form
                                                data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact
                                            Number</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-contact-number" class="text-[14px]">[To be populated from
                                                form data]</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Information Review -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Academic Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Grade Level</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-grade-level" class="text-[14px]">[To be populated from
                                                form data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Preferred
                                            Schedule</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-preferred-schedule" class="text-[14px]">[To be populated
                                                from form
                                                data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Primary
                                            Track</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-primary-track" class="text-[14px]">[To be populated from
                                                form data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Secondary
                                            Track/Strand</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-secondary-track" class="text-[14px]">[To be populated
                                                from form data]</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Review -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Contact Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Current
                                            Address</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-address" class="text-[14px]">[To be populated from form
                                                data]</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Guardian
                                            Information</label>
                                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                                            <span id="review-guardian" class="text-[14px]">[To be populated from form
                                                data]</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Certification Checkboxes -->
                        <div class="mt-8 space-y-4">
                            <div>
                                <label class="flex items-start">
                                    <input type="checkbox" id="certification" required
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                                    <span class="ml-2 text-sm text-gray-700">
                                        I hereby certify that all information provided is true and correct to the best
                                        of my knowledge. I understand that any false information may result in the
                                        rejection of my application. *
                                    </span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-start">
                                    <input type="checkbox" id="privacy" required
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                                    <span class="ml-2 text-sm text-gray-700">
                                        I agree to the Data Privacy Policy and consent to the collection and processing
                                        of my personal information for admission purposes. *
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <button type="button" id="prevBtn"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous
                            </button>
                            <button type="button" id="clearFormBtn"
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-xl shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Clear Form
                            </button>
                        </div>

                        <button type="button" id="nextBtn"
                            class="flex flex-row justify-center items-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none transition duration-200 truncate">
                            Next: Academic Info
                            <i class="fi fi-rr-angle-small-right"></i>
                        </button>

                        <button type="button" id="submitBtn"
                            class="hidden inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alert Container (using existing component) -->
    <x-alert type="success" />

    <!-- Loader Container (using existing component) -->
    <x-loader />

    <!-- Include JavaScript -->
    <script src="{{ asset('js/application-form.js') }}"></script>

    <!-- Import Alert and Loader utilities -->
    <script type="module">
        import {
            showAlert
        } from "/js/alert.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";

        // Make functions globally available
        window.showAlert = showAlert;
        window.showLoader = showLoader;
        window.hideLoader = hideLoader;
    </script>

    <!-- Add old() values to form fields for server-side validation persistence -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add old() values to form fields
            const oldValues = {
                @foreach (['middle_name', 'extension_name', 'birthdate', 'age', 'place_of_birth', 'mother_tongue', 'email', 'contact_number', 'grade_level', 'preferred_sched', 'primary_track', 'secondary_track', 'last_grade_level_completed', 'last_school_attended', 'last_school_year_completed', 'lrn', 'school_id', 'cur_house_no', 'cur_street', 'cur_barangay', 'cur_city', 'cur_province', 'cur_country', 'cur_zip_code', 'perm_house_no', 'perm_street', 'perm_barangay', 'perm_city', 'perm_province', 'perm_country', 'perm_zip_code', 'father_last_name', 'father_first_name', 'father_middle_name', 'father_contact_number', 'mother_last_name', 'mother_first_name', 'mother_middle_name', 'mother_contact_number', 'guardian_last_name', 'guardian_first_name', 'guardian_middle_name', 'guardian_contact_number'] as $field)
                    '{{ $field }}': '{{ old($field) }}',
                @endforeach
            };

            // Set values for text inputs, selects, etc.
            Object.entries(oldValues).forEach(([name, value]) => {
                if (value && value !== '') {
                    const field = document.querySelector(`[name="${name}"]`);
                    if (field && (field.type === 'text' || field.type === 'email' || field.type === 'tel' ||
                            field.type === 'number' || field.type === 'date' || field.tagName === 'SELECT'
                        )) {
                        field.value = value;
                    }
                }
            });

            // Set values for radio buttons and checkboxes
            @foreach (['gender', 'is_4ps_beneficiary', 'belongs_to_ip', 'is_returning', 'has_special_needs'] as $field)
                @if (old($field))
                    const {{ $field }}Field = document.querySelector(
                        `[name="{{ $field }}"][value="{{ old($field) }}"]`);
                    if ({{ $field }}Field) {{ $field }}Field.checked = true;
                @endif
            @endforeach

            // Handle special needs checkboxes
            @if (old('special_needs'))
                @foreach (old('special_needs') as $need)
                    const specialNeedField = document.querySelector(
                        `[name="special_needs[]"][value="{{ $need }}"]`);
                    if (specialNeedField) specialNeedField.checked = true;
                @endforeach
            @endif

            // Handle same as current address checkbox
            @if (old('sameAsCurrent'))
                document.getElementById('sameAsCurrent').checked = true;
            @endif
        });
    </script>

    <!-- Error Display (handled by AJAX now) -->
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.showAlert) {
                    window.showAlert('error', '{{ $errors->first() }}');
                }
            });
        </script>
    @endif
</body>

</html>
