@php
use App\Models\AcademicTerms;

$currentTerm = AcademicTerms::where('is_active', true)->first();
$allTerms = AcademicTerms::orderBy('year', 'desc')->orderBy('semester', 'desc')->get();
$selectedTermId = request()->get('term_id', $currentTerm?->id);
@endphp

@if(config('app.use_term_enrollments') && $allTerms->count() > 0)
<div class="flex items-center space-x-2 text-sm">
    <label for="term-selector" class="text-gray-600 font-medium">Academic Term:</label>
    <select 
        id="term-selector" 
        name="term_id" 
        class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        onchange="handleTermChange(this.value)"
    >
        @foreach($allTerms as $term)
            <option value="{{ $term->id }}" {{ $selectedTermId == $term->id ? 'selected' : '' }}>
                {{ $term->getFullNameAttribute() }}
                @if($term->is_active) (Active) @endif
            </option>
        @endforeach
    </select>
</div>

<script>
function updateUrlParameter(url, param, paramVal) {
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i = 0; i < tempArray.length; i++) {
            if (tempArray[i].split('=')[0] != param) {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }
    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

function handleTermChange(termId) {
    // Update URL
    const newUrl = updateUrlParameter(window.location.href, 'term_id', termId);
    
    // Check if DataTable exists and reload it
    if (typeof table1 !== 'undefined' && table1) {
        // Update URL without page reload
        window.history.pushState({}, '', newUrl);
        // Reload DataTable with new term_id
        table1.ajax.reload();
        // Reload enrollment statistics
        if (typeof loadEnrollmentStats !== 'undefined') {
            loadEnrollmentStats();
        }
    } else {
        // Fallback: full page reload
        window.location.href = newUrl;
    }
}
</script>
@endif
