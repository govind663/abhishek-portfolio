<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label><b>Name <span class="text-danger">*</span></b></label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Enter name"
                maxlength="255"
                required
                value="{{ old('name', $resume->name ?? '') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label><b>Title <span class="text-danger">*</span></b></label>
            <input type="text" name="title" id="title"
                class="form-control @error('title') is-invalid @enderror"
                placeholder="Enter title"
                maxlength="255"
                required
                value="{{ old('title', $resume->title ?? '') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label><b>Email Address <span class="text-danger">*</span></b></label>
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Enter email address"
                maxlength="255"
                autocomplete="email"
                required
                value="{{ old('email', $resume->email ?? '') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label><b>Phone Number <span class="text-danger">*</span></b></label>
            <input type="text" name="phone" id="phone"
                class="form-control @error('phone') is-invalid @enderror"
                placeholder="Enter phone number"
                maxlength="20"
                required
                value="{{ old('phone', $resume->phone ?? '') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label><b>Location <span class="text-danger">*</span></b></label>
            <input type="text" name="location" id="location"
                class="form-control @error('location') is-invalid @enderror"
                placeholder="Enter location"
                maxlength="255"
                required
                value="{{ old('location', $resume->location ?? '') }}">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label><b>Status <span class="text-danger">*</span></b></label>
            <select name="status" id="status"
                class="form-control custom-select2 @error('status') is-invalid @enderror"
                required>

                <option value="">Select Status</option>

                <option value="active"
                    {{ old('status', $resume->status ?? '') === 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive"
                    {{ old('status', $resume->status ?? '') === 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>

            </select>

            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label><b>Summary <span class="text-danger">*</span></b></label>
            <textarea name="summary" id="summary"
                class="form-control @error('summary') is-invalid @enderror"
                placeholder="Enter summary"
                rows="4"
                required>{{ old('summary', $resume->summary ?? '') }}</textarea>

            @error('summary')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>