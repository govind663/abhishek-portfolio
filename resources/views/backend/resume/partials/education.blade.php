<div id="education-wrapper">

@php
    $educations = old('educations', $resume->educations->toArray() ?? []);

    if (empty($educations)) {
        $educations = [[]];
    }
@endphp

@foreach($educations as $index => $education)

@php
    $edu = is_array($education) ? $education : (array) $education;
@endphp

<div class="education-item border rounded p-3 mb-3 position-relative bg-light">

    {{-- ID (IMPORTANT FOR UPDATE) --}}
    <input type="hidden" name="educations[{{ $index }}][id]" value="{{ $edu['id'] ?? '' }}">

    <div class="row">
        <div class="col-md-6">
            <label><b>Degree <span class="text-danger">*</span></b></label>
            <input type="text"
                name="educations[{{ $index }}][degree]"
                class="form-control @error("educations.$index.degree") is-invalid @enderror"
                value="{{ old("educations.$index.degree", $edu['degree'] ?? '') }}"
                required>
            @error("educations.$index.degree")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label><b>Field</b></label>
            <input type="text"
                name="educations[{{ $index }}][field]"
                class="form-control"
                value="{{ old("educations.$index.field", $edu['field'] ?? '') }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <label><b>Institution <span class="text-danger">*</span></b></label>
            <input type="text"
                name="educations[{{ $index }}][institution]"
                class="form-control @error("educations.$index.institution") is-invalid @enderror"
                value="{{ old("educations.$index.institution", $edu['institution'] ?? '') }}"
                required>
            @error("educations.$index.institution")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label><b>University</b></label>
            <input type="text"
                name="educations[{{ $index }}][university]"
                class="form-control"
                value="{{ old("educations.$index.university", $edu['university'] ?? '') }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <label><b>Location</b></label>
            <input type="text"
                name="educations[{{ $index }}][location]"
                class="form-control"
                value="{{ old("educations.$index.location", $edu['location'] ?? '') }}">
        </div>

        <div class="col-md-4">
            <label><b>Start Date <span class="text-danger">*</span></b></label>
            <input type="date"
                name="educations[{{ $index }}][start_date]"
                class="form-control @error("educations.$index.start_date") is-invalid @enderror"
                value="{{ old("educations.$index.start_date", $edu['start_date'] ?? '') }}"
                required>
            @error("educations.$index.start_date")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label><b>End Date</b></label>
            <input type="date"
                name="educations[{{ $index }}][end_date]"
                class="form-control"
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

{{-- ===================== --}}
{{-- FINAL JS (BUG-FREE) --}}
{{-- ===================== --}}
<script>
(function () {

    if (window.educationScriptLoaded) return;
    window.educationScriptLoaded = true;

    let wrapper = document.getElementById('education-wrapper');

    window.educationIndex = wrapper.children.length;

    document.addEventListener('click', function (e) {

        // =====================
        // ADD EDUCATION
        // =====================
        if (e.target && e.target.id === 'add-education') {

            let html = `
            <div class="education-item border rounded p-3 mb-3 position-relative bg-light">

                <input type="hidden" name="educations[${window.educationIndex}][id]" value="">

                <div class="row">
                    <div class="col-md-6">
                        <label><b>Degree *</b></label>
                        <input type="text" name="educations[${window.educationIndex}][degree]" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label><b>Field</b></label>
                        <input type="text" name="educations[${window.educationIndex}][field]" class="form-control">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <label><b>Institution *</b></label>
                        <input type="text" name="educations[${window.educationIndex}][institution]" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label><b>University</b></label>
                        <input type="text" name="educations[${window.educationIndex}][university]" class="form-control">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><b>Location</b></label>
                        <input type="text" name="educations[${window.educationIndex}][location]" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label><b>Start Date *</b></label>
                        <input type="date" name="educations[${window.educationIndex}][start_date]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label><b>End Date</b></label>
                        <input type="date" name="educations[${window.educationIndex}][end_date]" class="form-control">
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-sm remove-education position-absolute" style="top:10px; right:10px;">✕</button>
            </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
            window.educationIndex++;
        }

        // =====================
        // REMOVE EDUCATION
        // =====================
        if (e.target && e.target.classList.contains('remove-education')) {

            let items = wrapper.querySelectorAll('.education-item');

            // ❗ prevent deleting last item
            if (items.length <= 1) {
                alert('At least one education is required.');
                return;
            }

            e.target.closest('.education-item').remove();
        }

    });

})();
</script>