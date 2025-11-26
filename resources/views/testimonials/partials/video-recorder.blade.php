{{-- Video Recording Component for Mux --}}
<div id="video-recorder-container" class="video-recorder" style="display: none;">
    <input type="hidden" name="mux_upload_id" id="mux_upload_id">

    {{-- Recording States --}}
    <div id="video-state-permission" class="video-state" style="display: none;">
        <div class="video-placeholder">
            <i class="ti ti-camera"></i>
            <p>Camera permission required</p>
            <button type="button" class="btn btn-gradient" onclick="requestCameraPermission()">
                <i class="ti ti-camera me-2"></i> Enable Camera
            </button>
        </div>
    </div>

    <div id="video-state-preview" class="video-state" style="display: none;">
        <div class="video-container">
            <video id="video-preview" autoplay muted playsinline></video>
            <div class="video-overlay">
                <div class="timer" id="recording-timer" style="display: none;">
                    <span class="timer-dot"></span>
                    <span id="timer-text">00:00</span>
                </div>
            </div>
        </div>
        <div class="video-controls">
            <button type="button" id="btn-start-recording" class="btn-record" onclick="startRecording()">
                <i class="ti ti-player-record"></i>
            </button>
            <button type="button" id="btn-stop-recording" class="btn-stop" onclick="stopRecording()" style="display: none;">
                <i class="ti ti-player-stop"></i>
            </button>
        </div>
        <p class="video-hint" id="recording-hint">Click the button to start recording</p>
        <p class="video-hint" id="max-duration-hint" style="display: none;">
            Maximum duration: <span id="max-duration-text"></span>
        </p>
    </div>

    <div id="video-state-review" class="video-state" style="display: none;">
        <div class="video-container">
            <video id="video-playback" controls playsinline></video>
        </div>
        <div class="video-controls review-controls">
            @if($template->allow_video_retake ?? true)
            <button type="button" class="btn btn-outline-secondary" onclick="retakeVideo()">
                <i class="ti ti-refresh me-2"></i> Retake
            </button>
            @endif
            <button type="button" class="btn btn-gradient" id="btn-use-video" onclick="useVideo()">
                <i class="ti ti-check me-2"></i> Use This Video
            </button>
        </div>
    </div>

    <div id="video-state-uploading" class="video-state" style="display: none;">
        <div class="video-placeholder">
            <div class="upload-progress">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Uploading...</span>
                </div>
                <p>Uploading video...</p>
                <div class="progress" style="height: 8px; width: 200px;">
                    <div class="progress-bar" id="upload-progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted mt-2" id="upload-percent">0%</small>
            </div>
        </div>
    </div>

    <div id="video-state-ready" class="video-state" style="display: none;">
        <div class="video-container">
            <video id="video-final" controls playsinline></video>
        </div>
        <div class="video-controls">
            <div class="video-ready-badge">
                <i class="ti ti-check-circle"></i> Video ready for submission
            </div>
            @if($template->allow_video_retake ?? true)
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="retakeVideo()">
                <i class="ti ti-refresh me-2"></i> Record New Video
            </button>
            @endif
        </div>
    </div>

    <div id="video-state-error" class="video-state" style="display: none;">
        <div class="video-placeholder error">
            <i class="ti ti-alert-circle"></i>
            <p id="error-message">An error occurred</p>
            <button type="button" class="btn btn-outline-primary" onclick="resetVideoRecorder()">
                <i class="ti ti-refresh me-2"></i> Try Again
            </button>
        </div>
    </div>
</div>

<style>
    .video-recorder {
        background: #1a1a2e;
        border-radius: 12px;
        overflow: hidden;
        padding: 1rem;
    }

    .video-state {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .video-placeholder {
        aspect-ratio: 16/9;
        background: #16213e;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #667eea;
        gap: 1rem;
    }

    .video-placeholder.error {
        color: #dc3545;
    }

    .video-placeholder i {
        font-size: 4rem;
    }

    .video-placeholder p {
        color: #999;
        margin: 0;
    }

    .video-container {
        position: relative;
        aspect-ratio: 16/9;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
    }

    .video-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        background: linear-gradient(to bottom, rgba(0,0,0,0.5) 0%, transparent 100%);
    }

    .timer {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(0,0,0,0.6);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .timer-dot {
        width: 10px;
        height: 10px;
        background: #dc3545;
        border-radius: 50%;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .video-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem 0 0.5rem;
    }

    .review-controls {
        padding: 1rem 0;
    }

    .btn-record {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border: 4px solid #fff;
        background: #dc3545;
        color: white;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-record:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(220, 53, 69, 0.5);
    }

    .btn-stop {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border: 4px solid #fff;
        background: #dc3545;
        color: white;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        animation: recording 1s infinite;
    }

    @keyframes recording {
        0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5); }
        50% { box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
    }

    .video-hint {
        text-align: center;
        color: #999;
        font-size: 0.875rem;
        margin: 0.5rem 0 0;
    }

    .video-ready-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .upload-progress {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>

<script>
    // Video Recorder State Machine
    const VideoRecorder = {
        stream: null,
        mediaRecorder: null,
        recordedChunks: [],
        recordedBlob: null,
        maxDuration: {{ $template->max_video_duration ?? 120 }},
        timerInterval: null,
        recordingTime: 0,
        uploadId: null,
        uploadUrl: null,

        init() {
            this.showState('permission');
            document.getElementById('max-duration-text').textContent = this.formatTime(this.maxDuration);
        },

        showState(state) {
            document.querySelectorAll('.video-state').forEach(el => el.style.display = 'none');
            document.getElementById(`video-state-${state}`).style.display = 'block';
        },

        formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        },

        async requestPermission() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: true
                });

                const videoPreview = document.getElementById('video-preview');
                videoPreview.srcObject = this.stream;

                this.showState('preview');
                document.getElementById('max-duration-hint').style.display = 'block';
            } catch (error) {
                console.error('Camera permission denied:', error);
                this.showError('Camera permission denied. Please allow camera access and try again.');
            }
        },

        async startRecording() {
            try {
                this.recordedChunks = [];
                this.recordingTime = 0;

                // Create MediaRecorder
                const options = { mimeType: 'video/webm;codecs=vp9,opus' };
                if (!MediaRecorder.isTypeSupported(options.mimeType)) {
                    options.mimeType = 'video/webm;codecs=vp8,opus';
                }
                if (!MediaRecorder.isTypeSupported(options.mimeType)) {
                    options.mimeType = 'video/webm';
                }

                this.mediaRecorder = new MediaRecorder(this.stream, options);

                this.mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        this.recordedChunks.push(event.data);
                    }
                };

                this.mediaRecorder.onstop = () => {
                    this.recordedBlob = new Blob(this.recordedChunks, { type: 'video/webm' });
                    const videoUrl = URL.createObjectURL(this.recordedBlob);

                    const videoPlayback = document.getElementById('video-playback');
                    videoPlayback.src = videoUrl;

                    this.showState('review');
                };

                this.mediaRecorder.start(1000); // Collect data every second

                // Update UI
                document.getElementById('btn-start-recording').style.display = 'none';
                document.getElementById('btn-stop-recording').style.display = 'flex';
                document.getElementById('recording-timer').style.display = 'flex';
                document.getElementById('recording-hint').style.display = 'none';

                // Start timer
                this.timerInterval = setInterval(() => {
                    this.recordingTime++;
                    document.getElementById('timer-text').textContent = this.formatTime(this.recordingTime);

                    // Auto-stop at max duration
                    if (this.recordingTime >= this.maxDuration) {
                        this.stopRecording();
                    }
                }, 1000);

            } catch (error) {
                console.error('Failed to start recording:', error);
                this.showError('Failed to start recording. Please try again.');
            }
        },

        stopRecording() {
            if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
                this.mediaRecorder.stop();
            }

            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }

            document.getElementById('btn-start-recording').style.display = 'flex';
            document.getElementById('btn-stop-recording').style.display = 'none';
            document.getElementById('recording-timer').style.display = 'none';
        },

        retake() {
            // Reset state
            this.recordedBlob = null;
            this.recordedChunks = [];
            this.recordingTime = 0;
            this.uploadId = null;
            this.uploadUrl = null;

            document.getElementById('mux_upload_id').value = '';
            document.getElementById('timer-text').textContent = '00:00';
            document.getElementById('recording-hint').style.display = 'block';

            this.showState('preview');
        },

        async useVideo() {
            if (!this.recordedBlob) {
                this.showError('No video recorded');
                return;
            }

            this.showState('uploading');

            try {
                // 1. Get upload URL from our server
                const uploadResponse = await fetch('{{ route("api.mux.upload") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        template_id: {{ $template->id ?? 'null' }}
                    })
                });

                const uploadData = await uploadResponse.json();

                if (!uploadData.success) {
                    throw new Error(uploadData.message || 'Failed to create upload');
                }

                this.uploadId = uploadData.data.upload_id;
                this.uploadUrl = uploadData.data.upload_url;

                // 2. Upload video directly to Mux
                const xhr = new XMLHttpRequest();
                xhr.open('PUT', this.uploadUrl, true);

                xhr.upload.onprogress = (event) => {
                    if (event.lengthComputable) {
                        const percent = Math.round((event.loaded / event.total) * 100);
                        document.getElementById('upload-progress-bar').style.width = percent + '%';
                        document.getElementById('upload-percent').textContent = percent + '%';
                    }
                };

                xhr.onload = () => {
                    if (xhr.status === 200) {
                        // Store upload ID in form
                        document.getElementById('mux_upload_id').value = this.uploadId;

                        // Show ready state
                        const videoFinal = document.getElementById('video-final');
                        videoFinal.src = URL.createObjectURL(this.recordedBlob);

                        this.showState('ready');
                    } else {
                        throw new Error('Upload failed with status: ' + xhr.status);
                    }
                };

                xhr.onerror = () => {
                    this.showError('Upload failed. Please check your connection and try again.');
                };

                xhr.send(this.recordedBlob);

            } catch (error) {
                console.error('Upload error:', error);
                this.showError(error.message || 'Failed to upload video');
            }
        },

        showError(message) {
            document.getElementById('error-message').textContent = message;
            this.showState('error');
        },

        reset() {
            this.stopRecording();
            this.recordedBlob = null;
            this.recordedChunks = [];
            this.recordingTime = 0;
            this.uploadId = null;
            this.uploadUrl = null;

            document.getElementById('mux_upload_id').value = '';
            document.getElementById('timer-text').textContent = '00:00';
            document.getElementById('recording-hint').style.display = 'block';

            if (this.stream) {
                this.requestPermission();
            } else {
                this.showState('permission');
            }
        },

        cleanup() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
        }
    };

    // Global functions for onclick handlers
    function requestCameraPermission() {
        VideoRecorder.requestPermission();
    }

    function startRecording() {
        VideoRecorder.startRecording();
    }

    function stopRecording() {
        VideoRecorder.stopRecording();
    }

    function retakeVideo() {
        VideoRecorder.retake();
    }

    function useVideo() {
        VideoRecorder.useVideo();
    }

    function resetVideoRecorder() {
        VideoRecorder.reset();
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        VideoRecorder.cleanup();
    });
</script>
