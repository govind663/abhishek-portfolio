<div id="experience-wrapper">

@php
    $experiences = old('experiences', isset($resume) ? $resume->experiences->toArray() : []);

    if (empty($experiences)) {
        $experiences = [[]];
    }
@endphp

@foreach($experiences as $index => $exp)

@php
    $ex = is_array($exp) ? $exp : (array) $exp;

    // ✅ DESCRIPTION FIX (support details table also)
    $description = old("experiences.$index.description");

    if (!$description && !empty($ex['details'])) {
        $description = collect($ex['details'])
            ->pluck('description')
            ->filter()
            ->implode("\n");
    }
@endphp

<div class="experience-item border rounded p-3 mb-3 position-relative bg-light">

    {{-- ID (IMPORTANT FOR UPDATE) --}}
    <input type="hidden" name="experiences[{{ $index }}][id]" value="{{ $ex['id'] ?? '' }}">

    <div class="row">
        <div class="col-md-6">
            <label><b>Designation <span class="text-danger">*</span></b></label>
            <input type="text"
                name="experiences[{{ $index }}][designation]"
                class="form-control @error("experiences.$index.designation") is-invalid @enderror"
                value="{{ old("experiences.$index.designation", $ex['designation'] ?? '') }}">
        </div>

        <div class="col-md-6">
            <label><b>Company <span class="text-danger">*</span></b></label>
            <input type="text"
                name="experiences[{{ $index }}][company]"
                class="form-control @error("experiences.$index.company") is-invalid @enderror"
                value="{{ old("experiences.$index.company", $ex['company'] ?? '') }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <label><b>Location</b></label>
            <input type="text"
                name="experiences[{{ $index }}][location]"
                class="form-control"
                value="{{ old("experiences.$index.location", $ex['location'] ?? '') }}">
        </div>

        <div class="col-md-4">
            <label><b>Start Date <span class="text-danger">*</span></b></label>
            <input type="date"
                name="experiences[{{ $index }}][start_date]"
                class="form-control @error("experiences.$index.start_date") is-invalid @enderror"
                value="{{ old("experiences.$index.start_date", $ex['start_date'] ?? '') }}">
        </div>

        <div class="col-md-4">
            <label><b>End Date</b></label>
            <input type="date"
                name="experiences[{{ $index }}][end_date]"
                class="form-control"
                value="{{ old("experiences.$index.end_date", $ex['end_date'] ?? '') }}">
        </div>
    </div>

    <div class="mt-2">
        <label><b>Description</b></label>
        <textarea
            name="experiences[{{ $index }}][description]"
            class="form-control"
            rows="3">{{ $description }}</textarea>
    </div>

    {{-- REMOVE --}}
    <button type="button"
        class="btn btn-danger btn-sm remove-experience position-absolute"
        style="top:10px; right:10px;">
        ✕
    </button>

</div>
@endforeach

</div>

<button type="button" class="btn btn-primary btn-sm" id="add-experience">
    + Add Experience
</button>

{{-- ============================= --}}
{{-- FINAL JS (BUG FREE) --}}
{{-- ============================= --}}
<script>
(function () {

    if (window.experienceScriptLoaded) return;
    window.experienceScriptLoaded = true;

    let wrapper = document.getElementById('experience-wrapper');

    window.experienceIndex = wrapper.children.length;

    document.addEventListener('click', function (e) {

        // =======================
        // ADD EXPERIENCE
        // =======================
        if (e.target && e.target.id === 'add-experience') {

            let html = `
            <div class="experience-item border rounded p-3 mb-3 position-relative bg-light">

                <input type="hidden" name="experiences[${window.experienceIndex}][id]" value="">

                <div class="row">
                    <div class="col-md-6">
                        <label><b>Designation *</b></label>
                        <input type="text" name="experiences[${window.experienceIndex}][designation]" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label><b>Company *</b></label>
                        <input type="text" name="experiences[${window.experienceIndex}][company]" class="form-control">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><b>Location</b></label>
                        <input type="text" name="experiences[${window.experienceIndex}][location]" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label><b>Start Date *</b></label>
                        <input type="date" name="experiences[${window.experienceIndex}][start_date]" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label><b>End Date</b></label>
                        <input type="date" name="experiences[${window.experienceIndex}][end_date]" class="form-control">
                    </div>
                </div>

                <div class="mt-2">
                    <label><b>Description</b></label>
                    <textarea name="experiences[${window.experienceIndex}][description]" class="form-control" rows="3"></textarea>
                </div>

                <button type="button" class="btn btn-danger btn-sm remove-experience position-absolute" style="top:10px; right:10px;">✕</button>
            </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
            window.experienceIndex++;
        }

        // =======================
        // REMOVE EXPERIENCE
        // =======================
        if (e.target && e.target.classList.contains('remove-experience')) {

            let items = wrapper.querySelectorAll('.experience-item');

            // ❗ prevent deleting last item
            if (items.length <= 1) {
                alert('At least one experience is required.');
                return;
            }

            e.target.closest('.experience-item').remove();
        }

    });

})();
</script>