<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label><b>Name : <span class="text-danger">*</span></b></label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Enter name"
                maxlength="255"
                value="{{ old('name', isset($resume) ? $resume->name : '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label><b>Title : <span class="text-danger">*</span></b></label>
            <input type="text" name="title" id="title"
                class="form-control @error('title') is-invalid @enderror"
                placeholder="Enter title"
                maxlength="255"
                value="{{ old('title', isset($resume) ? $resume->title : '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label><b>Email Address : <span class="text-danger">*</span></b></label>
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Enter email address"
                maxlength="255"
                autocomplete="email"
                value="{{ old('email', isset($resume) ? $resume->email : '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label><b>Phone Number : <span class="text-danger">*</span></b></label>
            <input type="text" name="phone" id="phone"
                class="form-control @error('phone') is-invalid @enderror"
                placeholder="Enter phone number"
                maxlength="20"
                value="{{ old('phone', isset($resume) ? $resume->phone : '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label><b>Location : <span class="text-danger">*</span></b></label>
            <input type="text" name="location" id="location"
                class="form-control @error('location') is-invalid @enderror"
                placeholder="Enter location"
                maxlength="255"
                value="{{ old('location', isset($resume) ? $resume->location : '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label><b>Status : <span class="text-danger">*</span></b></label>
            <select name="status" id="status"
                class="form-control custom-select2 @error('status') is-invalid @enderror">

                <option value="">Select Status</option>

                <option value="active"
                    {{ old('status', isset($resume) ? $resume->status : '') == 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive"
                    {{ old('status', isset($resume) ? $resume->status : '') == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>

            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label><b>Summary : <span class="text-danger">*</span></b></label>
            <textarea name="summary" id="summary"
                class="form-control @error('summary') is-invalid @enderror"
                placeholder="Enter summary"
                rows="3">{{ old('summary', isset($resume) ? $resume->summary : '') }}</textarea>
        </div>
    </div>
</div>