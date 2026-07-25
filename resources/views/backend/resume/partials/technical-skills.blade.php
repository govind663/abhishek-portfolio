@php
    /*
    |--------------------------------------------------------------------------
    | SKILLS DATA HANDLING
    |--------------------------------------------------------------------------
    */
    $skills = old('skills');

    if (!$skills && isset($resume) && $resume) {
        $skills = $resume->skills
            ->map(fn ($item) => $item->toArray())
            ->toArray();
    }

    $skills = $skills ?: [[]];
@endphp

<div id="skills-wrapper">
    @foreach ($skills as $index => $skill)

        @php
            $sk = is_array($skill) ? $skill : $skill->toArray();
        @endphp

        <div class="skill-item card border mb-3">

            <div class="card-body position-relative">

                <input type="hidden"
                    name="skills[{{ $index }}][id]"
                    value="{{ $sk['id'] ?? '' }}">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>
                            <b>Skill Name <span class="text-danger">*</span></b>
                        </label>

                        <input type="text"
                            name="skills[{{ $index }}][skill_name]"
                            class="form-control @error("skills.$index.skill_name") is-invalid @enderror"
                            placeholder="Laravel, PHP"
                            value="{{ old("skills.$index.skill_name", $sk['skill_name'] ?? '') }}">

                        @error("skills.$index.skill_name")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>
                            <b>Category <span class="text-danger">*</span></b>
                        </label>

                        <input type="text"
                            name="skills[{{ $index }}][category]"
                            class="form-control @error("skills.$index.category") is-invalid @enderror"
                            placeholder="Backend, Frontend"
                            value="{{ old("skills.$index.category", $sk['category'] ?? '') }}">

                        @error("skills.$index.category")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>
                            <b>SVG Path</b>
                        </label>

                        <input type="text"
                            name="skills[{{ $index }}][icon_path]"
                            class="form-control icon-path"
                            placeholder="SVG Path"
                            value="{{ old("skills.$index.icon_path", $sk['icon_path'] ?? '') }}">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>
                            <b>ViewBox</b>
                        </label>

                        <input type="text"
                            name="skills[{{ $index }}][icon_viewbox]"
                            class="form-control icon-viewbox"
                            value="{{ old("skills.$index.icon_viewbox", $sk['icon_viewbox'] ?? '0 0 24 24') }}">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>
                            <b>Fill</b>
                        </label>

                        <input type="text"
                            name="skills[{{ $index }}][icon_fill]"
                            class="form-control icon-fill"
                            value="{{ old("skills.$index.icon_fill", $sk['icon_fill'] ?? '#000') }}">

                    </div>

                </div>

                <div class="mt-2">

                    <label>
                        <b>Preview</b>
                    </label>

                    <div class="svg-preview border rounded p-3 text-center bg-white">

                        <svg width="45"
                            height="45"
                            viewBox="{{ $sk['icon_viewbox'] ?? '0 0 24 24' }}"
                            fill="{{ $sk['icon_fill'] ?? '#000' }}">

                            <path d="{{ $sk['icon_path'] ?? '' }}"></path>

                        </svg>

                    </div>

                </div>

                {{-- Remove Button --}}
                @if(count($skills) > 1)
                <div class="position-relative p-3">
                    <button type="button"
                        class="btn btn-danger btn-sm remove-skill position-absolute"
                        style="top:15px;right:15px">

                        <i class="fa fa-trash"></i>

                    </button>
                </div>
                @endif

            </div>

        </div>

    @endforeach
</div>

<button type="button" id="add-skill" class="btn btn-primary btn-sm mt-2">
    <i class="fa fa-plus"></i>
    Add Skill
</button>

{{-- Technical Skills JS --}}
<script>
    (function () {

        if (window.skillLoaded) {
            return;
        }

        window.skillLoaded = true;

        const wrapper = document.getElementById('skills-wrapper');

        if (!wrapper) {
            return;
        }

        window.skillIndex = wrapper.querySelectorAll('.skill-item').length;

        /*
        |--------------------------------------------------------------------------
        | ADD / REMOVE SKILL
        |--------------------------------------------------------------------------
        */
        document.addEventListener('click', function (e) {

            if (e.target.closest('#add-skill')) {

                const index = window.skillIndex;

                const html = `
                    <div class="skill-item card border mb-3">

                        <div class="card-body position-relative">

                            <input type="hidden"
                                name="skills[${index}][id]"
                                value="">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>
                                        <b>Skill Name <span class="text-danger">*</span></b>
                                    </label>

                                    <input type="text"
                                        name="skills[${index}][skill_name]"
                                        class="form-control"
                                        placeholder="Laravel, PHP">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>
                                        <b>Category <span class="text-danger">*</span></b>
                                    </label>

                                    <input type="text"
                                        name="skills[${index}][category]"
                                        class="form-control"
                                        placeholder="Backend, Frontend">

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>
                                        <b>SVG Path</b>
                                    </label>

                                    <input type="text"
                                        name="skills[${index}][icon_path]"
                                        class="form-control icon-path"
                                        placeholder="SVG Path">

                                </div>

                                <div class="col-md-3 mb-3">

                                    <label>
                                        <b>ViewBox</b>
                                    </label>

                                    <input type="text"
                                        name="skills[${index}][icon_viewbox]"
                                        class="form-control icon-viewbox"
                                        value="0 0 24 24">

                                </div>

                                <div class="col-md-3 mb-3">

                                    <label>
                                        <b>Fill</b>
                                    </label>

                                    <input type="text"
                                        name="skills[${index}][icon_fill]"
                                        class="form-control icon-fill"
                                        value="#000">

                                </div>

                            </div>

                            <div class="mt-3">

                                <label>
                                    <b>Preview</b>
                                </label>

                                <div class="svg-preview border rounded p-3 text-center bg-white">

                                    <svg width="45"
                                        height="45"
                                        viewBox="0 0 24 24"
                                        fill="#000">

                                        <path d=""></path>

                                    </svg>

                                </div>

                            </div>

                            <button type="button"
                                    class="btn btn-danger btn-sm remove-skill position-absolute"
                                    style="top:15px;right:15px">

                                <i class="fa fa-trash"></i>

                            </button>

                        </div>

                    </div>
                `;

                wrapper.insertAdjacentHTML('beforeend', html);

                window.skillIndex++;
            }

            if (e.target.closest('.remove-skill')) {

                const items = wrapper.querySelectorAll('.skill-item');

                if (items.length <= 1) {
                    alert('At least one skill is required.');
                    return;
                }

                e.target.closest('.skill-item').remove();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | SVG PREVIEW UPDATE
        |--------------------------------------------------------------------------
        */
        function updateSvg(item) {

            const path = item.querySelector('.icon-path')?.value || '';
            const viewBox = item.querySelector('.icon-viewbox')?.value || '0 0 24 24';
            const fill = item.querySelector('.icon-fill')?.value || '#000';

            const svg = item.querySelector('svg');
            const pathTag = item.querySelector('svg path');

            if (svg) {
                svg.setAttribute('viewBox', viewBox);
                svg.setAttribute('fill', fill);
            }

            if (pathTag) {
                pathTag.setAttribute('d', path);
            }
        }

        document.addEventListener('input', function (e) {

            const item = e.target.closest('.skill-item');

            if (item) {
                updateSvg(item);
            }
        });

        wrapper.querySelectorAll('.skill-item').forEach(function (item) {
            updateSvg(item);
        });

    })();
</script>
