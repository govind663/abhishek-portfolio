@php
    /*
    |--------------------------------------------------------------------------
    | EDUCATION DATA HANDLING
    |--------------------------------------------------------------------------
    */
    $educations = old('educations');

    if (!$educations && isset($resume) && $resume) {
        $educations = $resume->educations->map(fn($item) => $item->toArray())->toArray();
    }

    $educations = $educations ?: [[]];

@endphp


<div id="education-wrapper">

    @foreach ($educations as $index => $education)
        @php
            $edu = is_array($education) ? $education : $education->toArray();
        @endphp

        <div class="education-item card border mb-3">

            <div class="card-body position-relative">

                {{-- Hidden ID --}}
                <input type="hidden" name="educations[{{ $index }}][id]" value="{{ $edu['id'] ?? '' }}">

                <div class="row">

                    {{-- Degree --}}
                    <div class="col-md-6 mb-3">

                        <label>
                            <b>
                                Degree
                                <span class="text-danger">*</span>
                            </b>
                        </label>

                        <input type="text" name="educations[{{ $index }}][degree]"
                            class="form-control @error("educations.$index.degree") is-invalid @enderror"
                            value="{{ old("educations.$index.degree", $edu['degree'] ?? '') }}"
                            placeholder="Enter degree">

                        @error("educations.$index.degree")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Field --}}
                    <div class="col-md-6 mb-3">

                        <label>
                            <b>Field</b>
                        </label>

                        <input type="text" name="educations[{{ $index }}][field]" class="form-control"
                            value="{{ old("educations.$index.field", $edu['field'] ?? '') }}" placeholder="Enter field">

                    </div>

                </div>

                <div class="row">

                    {{-- Institution --}}
                    <div class="col-md-6 mb-3">

                        <label>
                            <b>
                                Institution
                                <span class="text-danger">*</span>
                            </b>
                        </label>

                        <input type="text" name="educations[{{ $index }}][institution]"
                            class="form-control @error("educations.$index.institution") is-invalid @enderror"
                            value="{{ old("educations.$index.institution", $edu['institution'] ?? '') }}"
                            placeholder="Enter institution">

                        @error("educations.$index.institution")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- University --}}
                    <div class="col-md-6 mb-3">

                        <label>
                            <b>University</b>
                        </label>

                        <input type="text" name="educations[{{ $index }}][university]" class="form-control"
                            value="{{ old("educations.$index.university", $edu['university'] ?? '') }}"
                            placeholder="Enter university">

                    </div>

                </div>

                <div class="row">

                    {{-- Location --}}
                    <div class="col-md-4 mb-3">

                        <label>
                            <b>Location</b>
                        </label>

                        <input type="text" name="educations[{{ $index }}][location]" class="form-control"
                            value="{{ old("educations.$index.location", $edu['location'] ?? '') }}"
                            placeholder="Enter location">

                    </div>

                    {{-- Start Date --}}
                    <div class="col-md-4 mb-3">

                        <label>
                            <b>
                                Start Date
                                <span class="text-danger">*</span>
                            </b>
                        </label>

                        <input type="date" name="educations[{{ $index }}][start_date]"
                            class="form-control @error("educations.$index.start_date") is-invalid @enderror"
                            value="{{ old("educations.$index.start_date", $edu['start_date'] ?? '') }}">

                        @error("educations.$index.start_date")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- End Date --}}
                    <div class="col-md-4 mb-3">

                        <label>
                            <b>End Date</b>
                        </label>

                        <input type="date" name="educations[{{ $index }}][end_date]" class="form-control"
                            value="{{ old("educations.$index.end_date", $edu['end_date'] ?? '') }}">

                    </div>

                </div>

                {{-- Remove Button --}}
                @if(count($educations) > 1)
                <div class="position-relative p-3">
                    <button type="button"
                        class="btn btn-danger btn-sm remove-education position-absolute"
                        style="right:15px;top:15px">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                @endif

            </div>

        </div>
    @endforeach

</div>

<button type="button" id="add-education" class="btn btn-primary btn-sm mt-2">
    <i class="fa fa-plus"></i>
    Add Education
</button>

{{-- Education JS --}}
<script>
    (function () {

        if (window.educationLoaded) {
            return;
        }

        window.educationLoaded = true;

        const wrapper = document.getElementById('education-wrapper');

        if (!wrapper) {
            return;
        }

        window.educationIndex = wrapper.querySelectorAll('.education-item').length;

        document.addEventListener('click', function (e) {

            /*
            |--------------------------------------------------------------------------
            | ADD EDUCATION
            |--------------------------------------------------------------------------
            */
            if (e.target.closest('#add-education')) {

                const index = window.educationIndex;

                const html = `
                    <div class="education-item card border mb-3">
                        <div class="card-body position-relative">

                            <input type="hidden"
                                name="educations[${index}][id]"
                                value="">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>
                                        <b>Degree <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text"
                                        name="educations[${index}][degree]"
                                        class="form-control"
                                        placeholder="Enter degree">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>
                                        <b>Field</b>
                                    </label>
                                    <input type="text"
                                        name="educations[${index}][field]"
                                        class="form-control"
                                        placeholder="Enter field">
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>
                                        <b>Institution <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text"
                                        name="educations[${index}][institution]"
                                        class="form-control"
                                        placeholder="Enter institution">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>
                                        <b>University</b>
                                    </label>
                                    <input type="text"
                                        name="educations[${index}][university]"
                                        class="form-control"
                                        placeholder="Enter university">
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label>
                                        <b>Location</b>
                                    </label>
                                    <input type="text"
                                        name="educations[${index}][location]"
                                        class="form-control"
                                        placeholder="Enter location">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>
                                        <b>Start Date <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="date"
                                        name="educations[${index}][start_date]"
                                        class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>
                                        <b>End Date</b>
                                    </label>
                                    <input type="date"
                                        name="educations[${index}][end_date]"
                                        class="form-control">
                                </div>

                            </div>

                            <button type="button"
                                    class="btn btn-danger btn-sm remove-education position-absolute"
                                    style="top:15px; right:15px;">
                                <i class="fa fa-trash"></i>
                            </button>

                        </div>
                    </div>
                `;

                wrapper.insertAdjacentHTML('beforeend', html);

                window.educationIndex++;
            }

            /*
            |--------------------------------------------------------------------------
            | REMOVE EDUCATION
            |--------------------------------------------------------------------------
            */
            if (e.target.closest('.remove-education')) {

                const items = wrapper.querySelectorAll('.education-item');

                if (items.length <= 1) {
                    alert('At least one education is required.');
                    return;
                }

                e.target.closest('.education-item').remove();
            }

        });

    })();
</script>