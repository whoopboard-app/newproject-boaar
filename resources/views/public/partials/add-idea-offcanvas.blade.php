{{-- Add Idea Offcanvas Drawer --}}
{{-- Required variables: $categories, $feedbackSettings, $isLoggedIn, $publicUser --}}
@php
    $isLoggedIn = $isLoggedIn ?? false;
    $publicUser = $publicUser ?? null;
@endphp

<!-- Add Idea Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="addIdeaOffcanvas" aria-labelledby="addIdeaOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="addIdeaOffcanvasLabel">
            <i class="ti ti-bulb me-2" style="color: var(--primary-color);"></i>Share Your Idea
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Success/Error Alert -->
        <div id="ideaAlert" class="alert d-none mb-3" role="alert"></div>

        <form id="addIdeaForm" enctype="multipart/form-data">
            @csrf

            <!-- Idea Title -->
            <div class="mb-3">
                <label for="ideaTitle" class="form-label">Tell us your idea! <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="ideaTitle" name="idea" required
                       placeholder="What's your brilliant idea?">
                <div class="invalid-feedback" id="ideaError"></div>
            </div>

            <!-- Value Description -->
            <div class="mb-3">
                <label for="ideaValue" class="form-label">Why will your idea add more value to the product?</label>
                <textarea class="form-control" id="ideaValue" name="value_description" rows="3"
                          placeholder="Explain why this idea would be valuable..."></textarea>
                <div class="invalid-feedback" id="valueError"></div>
            </div>

            <!-- Full Name -->
            <div class="mb-3">
                <label for="ideaName" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="ideaName" name="full_name" required
                       placeholder="Enter your full name"
                       value="{{ $isLoggedIn && $publicUser ? $publicUser->full_name : '' }}">
                <div class="invalid-feedback" id="nameError"></div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="ideaEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="ideaEmail" name="email" required
                       placeholder="Enter your email address"
                       value="{{ $isLoggedIn && $publicUser ? $publicUser->email : '' }}"
                       {{ $isLoggedIn && $publicUser ? 'readonly' : '' }}>
                <div class="form-text">We'll send a confirmation link to this email.</div>
                <div class="invalid-feedback" id="emailError"></div>
            </div>

            <!-- Categories/Topics -->
            @if(isset($categories) && $categories->count() > 0)
            <div class="mb-3">
                <label class="form-label">Choose up to 3 topics for this idea (Optional)</label>
                <div class="topic-checkbox">
                    @foreach($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input topic-input" type="checkbox"
                                   name="categories[]" value="{{ $category->id }}"
                                   id="ideaCategory{{ $category->id }}">
                            <label class="form-check-label" for="ideaCategory{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="form-text">Select categories that best describe your idea.</div>
            </div>
            @endif

            <!-- Image Upload -->
            <div class="mb-3">
                <label class="form-label">Add Image (Optional)</label>
                <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('ideaImage').click()">
                    <i class="ti ti-photo-plus" style="font-size: 2rem; color: var(--text-secondary);"></i>
                    <p class="mb-0 mt-2 text-muted">Click to upload an image</p>
                    <small class="text-muted">PNG, JPG, WebP, AVIF up to 2MB</small>
                    <img id="imagePreview" class="image-preview d-none" alt="Preview">
                </div>
                <input type="file" class="d-none" id="ideaImage" name="image" accept="image/png,image/jpeg,image/jpg,image/webp,image/avif">
                <div class="invalid-feedback" id="imageError"></div>
            </div>

            <!-- hCaptcha -->
            @if($feedbackSettings->enable_captcha ?? false)
            <div class="mb-3">
                <div class="h-captcha" data-sitekey="{{ config('services.hcaptcha.sitekey') }}"></div>
                <div class="invalid-feedback d-block" id="captchaError"></div>
            </div>
            @endif

            <!-- Privacy Policy Checkbox -->
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="privacyAgree" name="privacy_agree" required>
                    <label class="form-check-label" for="privacyAgree">
                        I agree with the <a href="#" target="_blank">Privacy Policy</a> and <a href="#" target="_blank">Terms and Conditions</a> <span class="text-danger">*</span>
                    </label>
                    <div class="invalid-feedback" id="privacyError">You must agree to the Privacy Policy and Terms.</div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-add-idea" id="submitIdeaBtn">
                    <i class="ti ti-send me-1"></i> Submit Idea
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .topic-checkbox {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .topic-checkbox .form-check {
        background: #f9fafb;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        padding-left: 2rem;
        margin: 0;
        transition: all 0.2s;
    }

    .topic-checkbox .form-check:hover {
        border-color: var(--primary-color);
    }

    .topic-checkbox .form-check-input:checked + .form-check-label {
        color: var(--primary-color);
    }

    .topic-checkbox .form-check-input:checked ~ .form-check {
        border-color: var(--primary-color);
        background: rgba(88, 101, 242, 0.05);
    }

    /* Image upload */
    .image-upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .image-upload-area:hover {
        border-color: var(--primary-color);
        background: rgba(88, 101, 242, 0.02);
    }

    .image-upload-area.has-image {
        border-style: solid;
        border-color: var(--primary-color);
    }

    .image-preview {
        max-width: 100%;
        max-height: 150px;
        border-radius: 6px;
        margin-top: 0.75rem;
    }

    /* Success/Error alerts */
    .alert-idea-success {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-idea-error {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Add Idea Form Handling ===
    const addIdeaForm = document.getElementById('addIdeaForm');
    if (!addIdeaForm) return;

    const ideaAlert = document.getElementById('ideaAlert');
    const submitBtn = document.getElementById('submitIdeaBtn');
    const imageInput = document.getElementById('ideaImage');
    const imagePreview = document.getElementById('imagePreview');
    const imageUploadArea = document.getElementById('imageUploadArea');
    const topicCheckboxes = document.querySelectorAll('.topic-input');

    // Get config from parent page or use defaults
    const enableCaptcha = {{ ($feedbackSettings->enable_captcha ?? false) ? 'true' : 'false' }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const uniqueUrl = '{{ $settings->unique_url ?? "" }}';

    // Limit topic selection to 3
    topicCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checked = document.querySelectorAll('.topic-input:checked');
            if (checked.length > 3) {
                this.checked = false;
                showIdeaAlert('You can only select up to 3 topics.', 'warning');
            }
        });
    });

    // Image preview
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showIdeaAlert('Image size must be less than 2MB.', 'danger');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                    imageUploadArea.classList.add('has-image');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form submission
    addIdeaForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        hideIdeaAlert();

        // Validate privacy checkbox
        const privacyCheckbox = document.getElementById('privacyAgree');
        if (!privacyCheckbox.checked) {
            privacyCheckbox.classList.add('is-invalid');
            return;
        }

        // Validate hCaptcha if enabled
        if (enableCaptcha) {
            const hcaptchaResponse = document.querySelector('[name="h-captcha-response"]')?.value;
            if (!hcaptchaResponse) {
                document.getElementById('captchaError').textContent = 'Please complete the captcha.';
                return;
            }
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';

        try {
            const formData = new FormData(this);

            const response = await fetch('/' + uniqueUrl + '/feedback/submit', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Reset form
                addIdeaForm.reset();
                imagePreview.classList.add('d-none');
                imageUploadArea.classList.remove('has-image');

                // Reset hCaptcha if enabled
                if (enableCaptcha && typeof hcaptcha !== 'undefined') {
                    hcaptcha.reset();
                }

                // Close offcanvas immediately
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('addIdeaOffcanvas'));
                if (offcanvas) {
                    offcanvas.hide();
                }

                // Show success message on the main page
                showPageSuccessMessage(data.message || 'Your idea has been submitted! Please check your email to confirm.');
            } else {
                // Show validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = data.errors[field][0];
                            }
                        }
                    });
                }
                showIdeaAlert(data.message || 'Please correct the errors and try again.', 'danger');
            }
        } catch (error) {
            console.error('Submit error:', error);
            showIdeaAlert('An error occurred. Please try again.', 'danger');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-send me-1"></i> Submit Idea';
        }
    });

    function showIdeaAlert(message, type) {
        ideaAlert.className = `alert alert-${type} mb-3`;
        ideaAlert.textContent = message;
        ideaAlert.classList.remove('d-none');
    }

    function hideIdeaAlert() {
        ideaAlert.classList.add('d-none');
    }

    function showPageSuccessMessage(message) {
        // Try to use existing page alert if present
        const pageAlert = document.getElementById('pageSuccessAlert');
        const pageMessage = document.getElementById('pageSuccessMessage');

        if (pageAlert && pageMessage) {
            pageMessage.textContent = message;
            pageAlert.classList.remove('d-none');
            pageAlert.classList.add('show');
            window.scrollTo({ top: 0, behavior: 'smooth' });

            setTimeout(() => {
                pageAlert.classList.remove('show');
                setTimeout(() => {
                    pageAlert.classList.add('d-none');
                }, 150);
            }, 8000);
        } else {
            // Fallback: create a temporary alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.style.cssText = 'position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px; max-width: 90%;';
            alert.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="ti ti-circle-check me-2" style="font-size: 1.25rem;"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.body.appendChild(alert);

            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }, 8000);
        }
    }
});
</script>
