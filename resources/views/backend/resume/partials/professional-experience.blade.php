@php
    /*
    |--------------------------------------------------------------------------
    | EXPERIENCE DATA HANDLING
    |--------------------------------------------------------------------------
    */
    $resume = $resume ?? null;

    $experiences = old('experiences');

    if (!$experiences && $resume) {
        $experiences = $resume->experiences
            ->map(fn ($item) => $item->toArray())
            ->toArray();
    }

    $experiences = $experiences ?: [[]];
@endphp

<div id="experience-wrapper">

    @foreach ($experiences as $index => $experience)

        @php
            $ex = is_array($experience)
                ? $experience
                : $experience->toArray();
        @endphp

        <div class="experience-item card border mb-3">

            <div class="card-body position-relative">

                <input type="hidden"
                    name="experiences[{{ $index }}][id]"
                    value="{{ $ex['id'] ?? '' }}">

                <div class="row">

                    {{-- Designation --}}
                    <div class="col-md-6 mb-3">

                        <label>
                            <b>
                                Designation
                                <span class="text-danger">*</span>
                            </b>
                        </label>

                        <input type="text"
                            name="experiences[{{ $index }}][designation]"
                            class="form-control @error("experiences.$index.designation") is-invalid @enderror"
                            placeholder="Enter designation"
                            value="{{ old("experiences.$index.designation", $ex['designation'] ?? '') }}">

                        @error("experiences.$index.designation")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Company --}}
                    <div class="col-md-6 mb-3">

                        <label>
                            <b>
                                Company
                                <span class="text-danger">*</span>
                            </b>
                        </label>

                        <input type="text"
                            name="experiences[{{ $index }}][company]"
                            class="form-control @error("experiences.$index.company") is-invalid @enderror"
                            placeholder="Enter company"
                            value="{{ old("experiences.$index.company", $ex['company'] ?? '') }}">

                        @error("experiences.$index.company")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="row">

                    {{-- Location --}}
                    <div class="col-md-4 mb-3">

                        <label>
                            <b>Location</b>
                        </label>

                        <input type="text"
                            name="experiences[{{ $index }}][location]"
                            class="form-control"
                            placeholder="Enter location"
                            value="{{ old("experiences.$index.location", $ex['location'] ?? '') }}"
                        >

                    </div>

                    {{-- Start Date --}}
                    <div class="col-md-4 mb-3">

                        <label>
                            <b>
                                Start Date
                                <span class="text-danger">*</span>
                            </b>
                        </label>

                        <input type="date"
                            name="experiences[{{ $index }}][start_date]"
                            class="form-control @error("experiences.$index.start_date") is-invalid @enderror"
                            value="{{ old("experiences.$index.start_date", isset($ex['start_date']) ? \Illuminate\Support\Carbon::parse($ex['start_date'])->format('Y-m-d') : '') }}"
                        >

                        @error("experiences.$index.start_date")
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

                        <input type="date"
                            name="experiences[{{ $index }}][end_date]"
                            class="form-control"
                            value="{{ old("experiences.$index.end_date", isset($ex['end_date']) ? \Illuminate\Support\Carbon::parse($ex['end_date'])->format('Y-m-d') : '') }}"
                        >

                    </div>

                </div>

                {{-- Job Responsibilities --}}
                <div class="mb-3">

                    <label>
                        <b>
                            Job Responsibilities
                            <span class="text-danger">*</span>
                        </b>
                    </label>

                    @php
                        $details = old(
                            "experiences.$index.details",
                            $ex['details'] ?? []
                        );

                        if (empty($details)) {
                            $details = [
                                ['description' => '']
                            ];
                        }
                    @endphp

                    <table class="table table-bordered align-middle mb-2">

                        <thead class="table-light">

                            <tr>

                                <th width="90%">
                                    Description
                                </th>

                                <th width="10%" class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody class="experience-details-wrapper">

                            @foreach($details as $dIndex => $detail)

                                <tr>

                                    <td>

                                        <input type="hidden"
                                            name="experiences[{{ $index }}][details][{{ $dIndex }}][id]"
                                            value="{{ $detail['id'] ?? '' }}">

                                        <input type="text"
                                            name="experiences[{{ $index }}][details][{{ $dIndex }}][description]"
                                            class="form-control @error("experiences.$index.details.$dIndex.description") is-invalid @enderror"
                                            placeholder="Enter responsibility"
                                            value="{{ old("experiences.$index.details.$dIndex.description", $detail['description'] ?? '') }}">

                                        @error("experiences.$index.details.$dIndex.description")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </td>

                                    <td class="text-center align-middle">

                                        @if(count($details) > 1)

                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-detail">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                    <button type="button"
                        class="btn btn-primary btn-sm add-detail">

                        <i class="fa fa-plus"></i>
                        Add Responsibility

                    </button>

                </div>

                {{-- Remove Button --}}
                @if(count($experiences) > 1)
                <div class="position-relative p-3">
                    <button type="button"
                        class="btn btn-danger btn-sm remove-experience position-absolute mb-3"
                        style="top:15px;right:15px;">

                        <i class="fa fa-trash"></i>

                    </button>
                </div>
                @endif
            </div>

        </div>

    @endforeach

</div>

<button type="button" id="add-experience" class="btn btn-primary btn-sm mt-2">
    <i class="fa fa-plus"></i>
    Add Experience
</button>

{{-- Experience JS --}}
<script>
    (function () {

        if (window.experienceLoaded) {
            return;
        }

        window.experienceLoaded = true;

        const wrapper = document.getElementById('experience-wrapper');

        if (!wrapper) {
            return;
        }

        window.experienceIndex = wrapper.querySelectorAll('.experience-item').length;

        document.addEventListener('click', function (e) {

            /*
            |--------------------------------------------------------------------------
            | ADD EXPERIENCE
            |--------------------------------------------------------------------------
            */

            if (e.target.closest('#add-experience')) {

                let i = window.experienceIndex;

                let html = `

                <div class="experience-item border rounded p-3 mb-3 bg-light position-relative">

                    <input type="hidden"
                        name="experiences[${i}][id]"
                        value="">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label><b>Designation <span class="text-danger">*</span></b></label>

                            <input type="text"
                                name="experiences[${i}][designation]"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label><b>Company <span class="text-danger">*</span></b></label>

                            <input type="text"
                                name="experiences[${i}][company]"
                                class="form-control">

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label><b>Location</b></label>

                            <input type="text"
                                name="experiences[${i}][location]"
                                class="form-control">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label><b>Start Date</b></label>

                            <input type="date"
                                name="experiences[${i}][start_date]"
                                class="form-control">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label><b>End Date</b></label>

                            <input type="date"
                                name="experiences[${i}][end_date]"
                                class="form-control">

                        </div>

                    </div>

                    <div class="mb-3">

                        <label>
                            <b>Job Responsibilities</b>
                        </label>

                        <table class="table table-bordered align-middle mb-2">

                            <thead class="table-light">

                                <tr>

                                    <th width="90%">Description</th>

                                    <th width="10%" class="text-center">Action</th>

                                </tr>

                            </thead>

                            <tbody class="experience-details-wrapper">

                                <tr>

                                    <td>

                                        <input type="hidden"
                                            name="experiences[${i}][details][0][id]"
                                            value="">

                                        <input type="text"
                                            name="experiences[${i}][details][0][description]"
                                            class="form-control"
                                            placeholder="Enter responsibility">

                                    </td>

                                    <td class="text-center"></td>

                                </tr>

                            </tbody>

                        </table>

                        <button type="button"
                            class="btn btn-primary btn-sm add-detail">

                            <i class="fa fa-plus"></i>
                            Add Responsibility

                        </button>

                    </div>

                    <button type="button"
                        class="btn btn-danger btn-sm remove-experience position-absolute"
                        style="top:15px;right:15px;">

                        <i class="fa fa-trash"></i>

                    </button>

                </div>
                `;

                wrapper.insertAdjacentHTML('beforeend', html);

                window.experienceIndex++;
            }

            /*
            |--------------------------------------------------------------------------
            | REMOVE EXPERIENCE
            |--------------------------------------------------------------------------
            */

            if (e.target.closest('.remove-experience')) {

                let items = wrapper.querySelectorAll('.experience-item');

                if (items.length <= 1) {
                    alert('At least one experience is required.');
                    return;
                }

                e.target.closest('.experience-item').remove();
            }

            /*
            |--------------------------------------------------------------------------
            | ADD RESPONSIBILITY
            |--------------------------------------------------------------------------
            */

            if (e.target.closest('.add-detail')) {

                let expItem = e.target.closest('.experience-item');

                let tbody = expItem.querySelector('.experience-details-wrapper');

                let expIndex = Array.from(
                    wrapper.querySelectorAll('.experience-item')
                ).indexOf(expItem);

                let detailIndex = tbody.querySelectorAll('tr').length;

                let row = `

                    <tr>

                        <td>

                            <input type="hidden"
                                name="experiences[${expIndex}][details][${detailIndex}][id]"
                                value="">

                            <input type="text"
                                name="experiences[${expIndex}][details][${detailIndex}][description]"
                                class="form-control"
                                placeholder="Enter responsibility">

                        </td>

                        <td class="text-center">

                            <button type="button"
                                class="btn btn-danger btn-sm remove-detail">

                                <i class="fa fa-trash"></i>

                            </button>

                        </td>

                    </tr>
                `;

                tbody.insertAdjacentHTML('beforeend', row);
            }

            /*
            |--------------------------------------------------------------------------
            | REMOVE RESPONSIBILITY
            |--------------------------------------------------------------------------
            */

            if (e.target.closest('.remove-detail')) {

                let expItem = e.target.closest('.experience-item');

                let tbody = expItem.querySelector('.experience-details-wrapper');

                if (tbody.querySelectorAll('tr').length <= 1) {
                    alert('At least one responsibility is required.');
                    return;
                }

                e.target.closest('tr').remove();
            }

        });

    })();
</script>
