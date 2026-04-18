<form action="{{ route('frontend.contact.store') }}" method="post" data-aos="fade-up" data-aos-delay="600">
    @csrf

    <div class="row">
        <div class="col-lg-12">
            <h2 class="mb-4">Send Us A Message</h2>
        </div>
    </div>

    <div class="row gy-4">
        <!-- Name -->
        <div class="col-md-6 position-relative">
            <label for="name" class="mb-2">
                <strong>
                    <i class="bi bi-person-fill me-1"></i>
                    Name : * 
                </strong>
            </label>
            <input type="text" name="name" class="form-control icon-input @error('name') is-invalid @enderror" placeholder="Enter your name" value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Email -->
        <div class="col-md-6 position-relative">
            <label for="email" class="mb-2">
                <strong>
                    <i class="bi bi-envelope-fill me-1"></i>
                    Email  Id : *
                </strong>
            </label>
            <input type="email" name="email" class="form-control icon-input @error('email') is-invalid @enderror" placeholder="Enter your email id" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Phone -->
        <div class="col-md-6 position-relative">
            <label for="phone" class="mb-2">
                <strong>
                    <i class="bi bi-telephone-fill me-1"></i>
                    Phone Number : * 
                </strong>
            </label>
            <input type="text" name="phone" class="form-control icon-input @error('phone') is-invalid @enderror" placeholder="Enter your phone number" value="{{ old('phone') }}">
            @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Subject -->
        <div class="col-md-6 position-relative">
            <label for="subject" class="mb-2">
                <strong>
                    <i class="bi bi-chat-dots-fill me-1"></i>
                    Subject : * 
                </strong>
            </label>
            <input type="text" name="subject" class="form-control icon-input @error('subject') is-invalid @enderror" placeholder="How can I help you..?" value="{{ old('subject') }}">
            @error('subject')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Message -->
        <div class="col-md-12 position-relative">
            <label for="message" class="mb-2">
                <strong>
                    <i class="bi bi-chat-text-fill me-1"></i>
                    Message * : 
                </strong>
            </label>
            <textarea name="message" rows="6" class="form-control icon-textarea @error('message') is-invalid @enderror" placeholder="Write your message..." value="{{ old('message') }}">{{ old('message') }}</textarea>
            @error('message')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- reCAPTCHA -->
        {{-- <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div> --}}

        <!-- Submit Button -->
        <div class="col-md-12 text-center">
            <button type="submit" class="contact-btn">
                <i class="bi bi-send"></i>
                Send Message
            </button>
        </div>

    </div>
</form>