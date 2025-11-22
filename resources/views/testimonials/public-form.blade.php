<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $template->name }} - Testimonial</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: {{ $template->form_background_color ?? $template->page_background_color ?? '#667eea' }};
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .testimonial-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
        }

        .testimonial-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px 16px 0 0;
            text-align: center;
        }

        /* Wizard Steps */
        .wizard-steps {
            display: flex;
            justify-content: space-between;
            padding: 2rem 2rem 1rem;
            position: relative;
        }

        .wizard-steps::before {
            content: '';
            position: absolute;
            top: 50px;
            left: 25%;
            right: 25%;
            height: 2px;
            background: #e9ecef;
            z-index: 0;
        }

        .wizard-step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .wizard-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 600;
            color: #999;
            transition: all 0.3s;
        }

        .wizard-step.active .wizard-step-circle {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .wizard-step.completed .wizard-step-circle {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .wizard-step-title {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
        }

        .wizard-step.active .wizard-step-title {
            color: #667eea;
            font-weight: 600;
        }

        .step-content {
            display: none;
            padding: 0 2rem 2rem;
        }

        .step-content.active {
            display: block;
        }

        /* Rating Styles */
        .rating-container {
            text-align: center;
            padding: 2rem 0;
        }

        .rating-stars {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            font-size: 3rem;
            margin: 2rem 0;
        }

        .rating-star {
            cursor: pointer;
            color: #ddd;
            transition: all 0.2s;
        }

        .rating-star.active,
        .rating-star:hover {
            color: #ffc107;
            transform: scale(1.1);
        }

        .smile-rating {
            display: flex;
            justify-content: center;
            gap: 1rem;
            font-size: 3rem;
            margin: 2rem 0;
        }

        .smile-icon {
            cursor: pointer;
            opacity: 0.3;
            transition: all 0.2s;
            filter: grayscale(100%);
        }

        .smile-icon.active,
        .smile-icon:hover {
            opacity: 1;
            transform: scale(1.2);
            filter: grayscale(0%);
        }

        /* Type Selector */
        .type-selector {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
        }

        .type-option {
            flex: 1;
            padding: 2rem;
            border: 3px solid #e9ecef;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }

        .type-option:hover {
            border-color: #667eea;
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .type-option.active {
            border-color: #667eea;
            background: #667eea;
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .type-option i {
            font-size: 3rem;
            display: block;
            margin-bottom: 1rem;
        }

        .type-option-title {
            font-weight: 600;
            font-size: 1.125rem;
        }

        /* Navigation Buttons */
        .step-navigation {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Success Animation */
        .success-animation {
            text-align: center;
            padding: 3rem 2rem;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease-out;
        }

        .success-icon i {
            font-size: 3rem;
            color: white;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="testimonial-card">
            <div class="testimonial-header">
                <h2 class="mb-2">{{ $template->name }}</h2>
                <p class="mb-0 opacity-75">Share your experience with us</p>
            </div>

            <!-- Wizard Steps Indicator -->
            <div class="wizard-steps">
                <div class="wizard-step active" data-step="1">
                    <div class="wizard-step-circle">1</div>
                    <div class="wizard-step-title">Rate</div>
                </div>
                <div class="wizard-step" data-step="2">
                    <div class="wizard-step-circle">2</div>
                    <div class="wizard-step-title">Testimonial</div>
                </div>
                <div class="wizard-step" data-step="3">
                    <div class="wizard-step-circle">3</div>
                    <div class="wizard-step-title">Details</div>
                </div>
                <div class="wizard-step" data-step="4">
                    <div class="wizard-step-circle">4</div>
                    <div class="wizard-step-title">Submit</div>
                </div>
            </div>

            <form action="{{ route('testimonials.public.store', $template->unique_url) }}" method="POST" enctype="multipart/form-data" id="testimonial-form">
                @csrf

                <!-- Step 1: Rating -->
                <div class="step-content active" data-step="1">
                    <div class="rating-container">
                        @if($template->collect_rating)
                            <h4 class="mb-4">How would you rate your experience?</h4>

                            @if($template->rating_style === 'star')
                                <div class="rating-stars">
                                    <i class="rating-star ti ti-star" data-rating="1" onclick="setRating(1)"></i>
                                    <i class="rating-star ti ti-star" data-rating="2" onclick="setRating(2)"></i>
                                    <i class="rating-star ti ti-star" data-rating="3" onclick="setRating(3)"></i>
                                    <i class="rating-star ti ti-star" data-rating="4" onclick="setRating(4)"></i>
                                    <i class="rating-star ti ti-star" data-rating="5" onclick="setRating(5)"></i>
                                </div>
                            @else
                                <div class="smile-rating">
                                    <span class="smile-icon" data-rating="1" onclick="setRating(1)">😢</span>
                                    <span class="smile-icon" data-rating="2" onclick="setRating(2)">🙁</span>
                                    <span class="smile-icon" data-rating="3" onclick="setRating(3)">😐</span>
                                    <span class="smile-icon" data-rating="4" onclick="setRating(4)">🙂</span>
                                    <span class="smile-icon" data-rating="5" onclick="setRating(5)">😊</span>
                                </div>
                            @endif

                            <input type="hidden" name="rating" id="rating-value">
                            <p class="text-muted"><small>Click to select your rating</small></p>
                        @else
                            <h4 class="mb-3">Welcome!</h4>
                            <p class="text-muted">Thank you for taking the time to share your feedback with us.</p>
                        @endif
                    </div>

                    <div class="step-navigation">
                        <div></div>
                        <button type="button" class="btn btn-gradient" onclick="nextStep()">
                            Next <i class="ti ti-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Testimonial Content -->
                <div class="step-content" data-step="2">
                    <h4 class="mb-4">Share Your Experience</h4>

                    <!-- Type Selection -->
                    @if($template->collect_text && $template->collect_video)
                        <div class="type-selector">
                            <div class="type-option active" onclick="selectType('text')">
                                <i class="ti ti-file-text"></i>
                                <div class="type-option-title">Write Testimonial</div>
                                <small class="d-block mt-2 opacity-75">Share your thoughts in text</small>
                            </div>
                            <div class="type-option" onclick="selectType('video')">
                                <i class="ti ti-video"></i>
                                <div class="type-option-title">Video Testimonial</div>
                                <small class="d-block mt-2 opacity-75">Share a video URL</small>
                            </div>
                        </div>
                        <input type="hidden" name="type" id="testimonial-type" value="text">
                    @elseif($template->collect_video)
                        <input type="hidden" name="type" value="video">
                    @else
                        <input type="hidden" name="type" value="text">
                    @endif

                    <!-- Text Content -->
                    @if($template->collect_text)
                        <div id="text-content-field" class="mb-3">
                            <label for="text_content" class="form-label">Your Testimonial <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('text_content') is-invalid @enderror" id="text_content" name="text_content" rows="6" placeholder="Share your experience with us..." required>{{ old('text_content') }}</textarea>
                            @error('text_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <!-- Video URL -->
                    @if($template->collect_video)
                        <div id="video-url-field" class="mb-3" style="display: {{ $template->collect_text ? 'none' : 'block' }};">
                            <label for="video_url" class="form-label">Video URL (YouTube, Vimeo, etc.) <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('video_url') is-invalid @enderror" id="video_url" name="video_url" placeholder="https://youtube.com/watch?v=..." value="{{ old('video_url') }}">
                            <small class="text-muted">Paste your video link from YouTube, Vimeo, or other platforms</small>
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="step-navigation">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </button>
                        <button type="button" class="btn btn-gradient" onclick="nextStep()">
                            Next <i class="ti ti-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Personal Details -->
                <div class="step-content" data-step="3">
                    <h4 class="mb-4">Your Information</h4>

                    @if($template->field_full_name)
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="full_name" name="name" required value="{{ old('name') }}" placeholder="John Doe">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="row">
                            @if($template->field_first_name)
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required placeholder="John">
                                </div>
                            @endif
                            @if($template->field_last_name)
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required placeholder="Doe">
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($template->field_email)
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="john@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="row">
                        @if($template->field_company)
                            <div class="col-md-6 mb-3">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company') }}" placeholder="Company Name">
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if($template->field_position)
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" placeholder="Job Title">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>

                    @if($template->field_social_url)
                        <div class="mb-3">
                            <label for="social_url" class="form-label">Social Media URL</label>
                            <input type="url" class="form-control" id="social_url" name="social_url" placeholder="https://linkedin.com/in/yourprofile">
                        </div>
                    @endif

                    <div class="step-navigation">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </button>
                        <button type="button" class="btn btn-gradient" onclick="nextStep()">
                            Next <i class="ti ti-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Photo & Submit -->
                <div class="step-content" data-step="4">
                    <h4 class="mb-4">Almost Done!</h4>

                    <div class="mb-4">
                        <label for="avatar" class="form-label">Add Your Photo (Optional)</label>
                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">
                        <small class="text-muted">A photo helps make your testimonial more personal and trustworthy</small>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="avatar-preview" class="mt-3" style="display: none;">
                            <img id="avatar-preview-img" src="" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea;">
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <small>By submitting this testimonial, you agree to let us use your feedback for promotional purposes.</small>
                    </div>

                    <div class="step-navigation">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="ti ti-send me-1"></i> Submit Testimonial
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentStep = 1;
        const totalSteps = 4;

        // Navigate to next step
        function nextStep() {
            if (currentStep < totalSteps) {
                // Mark current step as completed
                document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.add('completed');

                currentStep++;
                updateStepDisplay();
            }
        }

        // Navigate to previous step
        function previousStep() {
            if (currentStep > 1) {
                currentStep--;

                // Remove completed class from current step
                document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.remove('completed');

                updateStepDisplay();
            }
        }

        // Update step display
        function updateStepDisplay() {
            // Update step indicators
            document.querySelectorAll('.wizard-step').forEach((step, index) => {
                step.classList.remove('active');
                const stepNumber = index + 1;
                if (stepNumber === currentStep) {
                    step.classList.add('active');
                }
            });

            // Update step content
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelector(`.step-content[data-step="${currentStep}"]`).classList.add('active');

            // Scroll to top of card
            document.querySelector('.testimonial-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Rating functionality
        function setRating(rating) {
            document.getElementById('rating-value').value = rating;

            @if($template->rating_style === 'star')
                const stars = document.querySelectorAll('.rating-star');
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            @else
                const smiles = document.querySelectorAll('.smile-icon');
                smiles.forEach((smile, index) => {
                    if (index + 1 === rating) {
                        smile.classList.add('active');
                    } else {
                        smile.classList.remove('active');
                    }
                });
            @endif
        }

        // Type selection functionality
        function selectType(type) {
            const typeField = document.getElementById('testimonial-type');
            if (typeField) {
                typeField.value = type;
            }

            const options = document.querySelectorAll('.type-option');
            options.forEach(option => option.classList.remove('active'));
            event.target.closest('.type-option').classList.add('active');

            // Show/hide fields
            const textField = document.getElementById('text-content-field');
            const videoField = document.getElementById('video-url-field');

            if (type === 'text') {
                if (textField) textField.style.display = 'block';
                if (videoField) videoField.style.display = 'none';
                document.getElementById('text_content').required = true;
                if (document.getElementById('video_url')) {
                    document.getElementById('video_url').required = false;
                }
            } else {
                if (textField) textField.style.display = 'none';
                if (videoField) videoField.style.display = 'block';
                document.getElementById('text_content').required = false;
                document.getElementById('video_url').required = true;
            }
        }

        // Avatar preview
        document.getElementById('avatar')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview-img').src = e.target.result;
                    document.getElementById('avatar-preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('avatar-preview').style.display = 'none';
            }
        });

        // Handle form submission with name combination
        document.getElementById('testimonial-form').addEventListener('submit', function(e) {
            const firstName = document.getElementById('first_name');
            const lastName = document.getElementById('last_name');
            const fullName = document.getElementById('full_name');

            // If using first + last name, combine them
            if (firstName && lastName && !fullName) {
                // Create hidden input with combined name
                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'name';
                nameInput.value = `${firstName.value} ${lastName.value}`.trim();
                this.appendChild(nameInput);
            }
        });
    </script>
</body>
</html>
