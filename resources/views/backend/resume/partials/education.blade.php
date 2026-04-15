<div id="education-wrapper">

@php
    $educations = old('educations', isset($resume) ? $resume->educations->toArray() : []);

    if (empty($educations)) {
        $educations = [[]];
    }
@endphp

@foreach($educations as $index => $education)

@php
    // 🔥 SAFE ACCESS (array/object dono support)
    $edu = is_array($education) ? $education : (array) $education;
@endphp

<div class="education-item border rounded p-3 mb-3 position-relative bg-light">

    {{-- ✅ IMPORTANT (FOR UPDATE) --}}
    <input type="hidden" name="educations[{{ $index }}][id]" value="{{ $edu['id'] ?? '' }}">

    <div class="row">
        <div class="col-md-6">
            <label><b>Degree : <span class="text-danger">*</span></b></label>
            <input type="text"
                name="educations[{{ $index }}][degree]"
                class="form-control @error("educations.$index.degree") is-invalid @enderror"
                placeholder="Degree * (eg. BSc, HSC, etc.)"
                value="{{ old("educations.$index.degree", $edu['degree'] ?? '') }}">
        </div>

        <div class="col-md-6">
            <label><b>Field : </b></label>
            <input type="text"
                name="educations[{{ $index }}][field]"
                class="form-control @error("educations.$index.field") is-invalid @enderror"
                placeholder="Field of study"
                value="{{ old("educations.$index.field", $edu['field'] ?? '') }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <label><b>Institution : <span class="text-danger">*</span></b></label>
            <input type="text"
                name="educations[{{ $index }}][institution]"
                class="form-control @error("educations.$index.institution") is-invalid @enderror"
                placeholder="Institution name"
                value="{{ old("educations.$index.institution", $edu['institution'] ?? '') }}">
        </div>

        <div class="col-md-6">
            <label><b>University : </b></label>
            <input type="text"
                name="educations[{{ $index }}][university]"
                class="form-control @error("educations.$index.university") is-invalid @enderror"
                placeholder="University name"
                value="{{ old("educations.$index.university", $edu['university'] ?? '') }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <label><b>Location : </b></label>
            <input type="text"
                name="educations[{{ $index }}][location]"
                class="form-control @error("educations.$index.location") is-invalid @enderror"
                placeholder="Location"
                value="{{ old("educations.$index.location", $edu['location'] ?? '') }}">
        </div>

        <div class="col-md-4">
            <label><b>Start Date : <span class="text-danger">*</span></b></label>
            <input type="text"
                name="educations[{{ $index }}][start_date]"
                class="form-control date-picker @error("educations.$index.start_date") is-invalid @enderror"
                placeholder="Select Start Date"
                value="{{ old("educations.$index.start_date", $edu['start_date'] ?? '') }}">
        </div>

        <div class="col-md-4">
            <label><b>End Date : </b></label>
            <input type="text"
                name="educations[{{ $index }}][end_date]"
                class="form-control date-picker @error("educations.$index.end_date") is-invalid @enderror"
                placeholder="Select End Date"
                value="{{ old("educations.$index.end_date", $edu['end_date'] ?? '') }}">
        </div>
    </div>

    <button type="button"
        class="btn btn-danger btn-sm remove-education position-absolute"
        style="top:10px; right:10px;">
        ✕
    </button>

</div>
@endforeach

</div>

<button type="button" class="btn btn-primary btn-sm" id="add-education">
+ Add Education
</button>

<script>
(function () {

    if (window.educationScriptLoaded) return;
    window.educationScriptLoaded = true;

    window.educationIndex = {{ count($educations) }};

    document.addEventListener('click', function (e) {

        // ADD
        if (e.target && e.target.id === 'add-education') {

            let wrapper = document.getElementById('education-wrapper');

            let html = `
            <div class="education-item border rounded p-3 mb-3 position-relative bg-light">

                <input type="hidden" name="educations[${window.educationIndex}][id]" value="">

                <div class="row">
                    <div class="col-md-6">
                        <label><b>Degree : <span class="text-danger">*</span></b></label>
                        <input type="text" name="educations[${window.educationIndex}][degree]" class="form-control" placeholder="Degree *">
                    </div>
                    <div class="col-md-6">
                        <label><b>Field : </b></label>
                        <input type="text" name="educations[${window.educationIndex}][field]" class="form-control" placeholder="Field">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <label><b>Institution : <span class="text-danger">*</span></b></label>
                        <input type="text" name="educations[${window.educationIndex}][institution]" class="form-control" placeholder="Institution *">
                    </div>
                    <div class="col-md-6">
                        <label><b>University : </b></label>
                        <input type="text" name="educations[${window.educationIndex}][university]" class="form-control" placeholder="University">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><b>Location : </b></label>
                        <input type="text" name="educations[${window.educationIndex}][location]" class="form-control" placeholder="Location">
                    </div>
                    <div class="col-md-4">
                        <label><b>Start Date : <span class="text-danger">*</span></b></label>
                        <input type="text" name="educations[${window.educationIndex}][start_date]" class="form-control date-picker" placeholder="Start Date">
                    </div>
                    <div class="col-md-4">
                        <label><b>End Date : </b></label>
                        <input type="text" name="educations[${window.educationIndex}][end_date]" class="form-control date-picker" placeholder="End Date">
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-sm remove-education position-absolute" style="top:10px; right:10px;">✕</button>
            </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
            window.educationIndex++;
        }

        // REMOVE
        if (e.target && e.target.classList.contains('remove-education')) {
            e.target.closest('.education-item').remove();
        }

    });

})();
</script>