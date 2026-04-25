<div id="skills-wrapper">

@php
    $skills = old('skills', isset($resume) ? $resume->skills->toArray() : []);

    if (empty($skills)) {
        $skills = [[]];
    }
@endphp

@foreach($skills as $index => $skill)

@php
    $sk = is_array($skill) ? $skill : (array) $skill;

    $iconPath = old("skills.$index.icon_path", $sk['icon_path'] ?? '');
    $viewBox = old("skills.$index.icon_viewbox", $sk['icon_viewbox'] ?? '0 0 24 24');
    $fill = old("skills.$index.icon_fill", $sk['icon_fill'] ?? '#000');
@endphp

<div class="skill-item border rounded p-3 mb-3 position-relative bg-light">

    <input type="hidden" name="skills[{{ $index }}][id]" value="{{ $sk['id'] ?? '' }}">

    <div class="row">
        <div class="col-md-6">
            <label><b>Skill Name : <span class="text-danger">*</span></b></label>
            <input type="text"
                name="skills[{{ $index }}][skill_name]"
                class="form-control @error("skills.$index.skill_name") is-invalid @enderror"
                placeholder="e.g. PHP, Laravel"
                value="{{ old("skills.$index.skill_name", $sk['skill_name'] ?? '') }}">
        </div>

        <div class="col-md-6">
            <label><b>Category : <span class="text-danger">*</span></b></label>
            <input type="text"
                name="skills[{{ $index }}][category]"
                class="form-control @error("skills.$index.category") is-invalid @enderror"
                placeholder="Backend, Frontend"
                value="{{ old("skills.$index.category", $sk['category'] ?? '') }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <label><b>Icon Path :</b></label>
            <input type="text"
                name="skills[{{ $index }}][icon_path]"
                class="form-control icon-path"
                placeholder="SVG path"
                value="{{ e($iconPath) }}">
        </div>

        <div class="col-md-3">
            <label><b>ViewBox :</b></label>
            <input type="text"
                name="skills[{{ $index }}][icon_viewbox]"
                class="form-control icon-viewbox"
                value="{{ $viewBox }}">
        </div>

        <div class="col-md-3">
            <label><b>Fill :</b></label>
            <input type="text"
                name="skills[{{ $index }}][icon_fill]"
                class="form-control icon-fill"
                value="{{ $fill }}">
        </div>
    </div>

    {{-- SVG PREVIEW --}}
    <div class="mt-3">
        <label><b>Preview :</b></label>
        <div class="svg-preview border p-2 text-center bg-white">
            <svg width="40" height="40"
                viewBox="{{ $viewBox }}"
                fill="{{ $fill }}">
                <path d="{{ e($iconPath) }}"></path>
            </svg>
        </div>
    </div>

    <button type="button"
        class="btn btn-danger btn-sm remove-skill position-absolute"
        style="top:10px; right:10px;">
        ✕
    </button>

</div>
@endforeach

</div>

<button type="button" class="btn btn-primary btn-sm" id="add-skill">
    + Add Skill
</button>

<script>
(function () {

    if (window.skillScriptLoaded) return;
    window.skillScriptLoaded = true;

    window.skillIndex = {{ count($skills) }};

    document.addEventListener('click', function (e) {

        if (e.target && e.target.id === 'add-skill') {

            let wrapper = document.getElementById('skills-wrapper');
            if (!wrapper) return;

            let html = `
            <div class="skill-item border rounded p-3 mb-3 position-relative bg-light">

                <input type="hidden" name="skills[${window.skillIndex}][id]" value="">

                <div class="row">
                    <div class="col-md-6">
                        <label><b>Skill Name *</b></label>
                        <input type="text" name="skills[${window.skillIndex}][skill_name]" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label><b>Category *</b></label>
                        <input type="text" name="skills[${window.skillIndex}][category]" class="form-control">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <label><b>Icon Path</b></label>
                        <input type="text" name="skills[${window.skillIndex}][icon_path]" class="form-control icon-path">
                    </div>
                    <div class="col-md-3">
                        <label><b>ViewBox</b></label>
                        <input type="text" name="skills[${window.skillIndex}][icon_viewbox]" class="form-control icon-viewbox" value="0 0 24 24">
                    </div>
                    <div class="col-md-3">
                        <label><b>Fill</b></label>
                        <input type="text" name="skills[${window.skillIndex}][icon_fill]" class="form-control icon-fill" value="#000">
                    </div>
                </div>

                <div class="mt-3">
                    <label><b>Preview</b></label>
                    <div class="svg-preview border p-2 text-center bg-white">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="#000">
                            <path d=""></path>
                        </svg>
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-sm remove-skill position-absolute" style="top:10px; right:10px;">✕</button>
            </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
            window.skillIndex++;
        }

        if (e.target && e.target.classList.contains('remove-skill')) {
            let item = e.target.closest('.skill-item');
            if (item) item.remove();
        }

    });

    // 🔥 LIVE SVG PREVIEW
    document.addEventListener('input', function (e) {

        let item = e.target.closest('.skill-item');
        if (!item) return;

        let path = item.querySelector('.icon-path')?.value || '';
        let viewBox = item.querySelector('.icon-viewbox')?.value || '0 0 24 24';
        let fill = item.querySelector('.icon-fill')?.value || '#000';

        let svg = item.querySelector('.svg-preview svg');
        let pathEl = item.querySelector('.svg-preview path');

        if (!svg || !pathEl) return;

        try {
            svg.setAttribute('viewBox', viewBox);
            svg.setAttribute('fill', fill);
            pathEl.setAttribute('d', path);
        } catch (err) {
            console.warn('SVG update error:', err);
        }
    });

})();
</script>