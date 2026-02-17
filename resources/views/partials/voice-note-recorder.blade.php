<div class="voice-note-recorder mb-3">
    <label class="form-label" style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem;">
        <i class="fas fa-microphone text-primary me-2"></i> {{ __('messages.voice_recorder_title') }}
    </label>

    <div class="voice-recorder-container">
        <div class="recorder-controls d-flex align-items-center gap-3 mb-2">
            <button type="button" class="btn btn-outline-primary btn-sm record-btn" id="recordBtn">
                <i class="fas fa-microphone"></i>
                <span class="btn-text">{{ __('messages.recorder_start_btn') }}</span>
            </button>

            <button type="button" class="btn btn-outline-danger btn-sm stop-btn" id="stopBtn" style="display: none;">
                <i class="fas fa-stop"></i>
                <span class="btn-text">{{ __('messages.recorder_stop_btn') }}</span>
            </button>

            <button type="button" class="btn btn-outline-secondary btn-sm play-btn" id="playBtn" style="display: none;">
                <i class="fas fa-play"></i>
                <span class="btn-text">{{ __('messages.recorder_play_btn') }}</span>
            </button>

            <button type="button" class="btn btn-outline-warning btn-sm delete-btn" id="deleteBtn" style="display: none;">
                <i class="fas fa-trash"></i>
                <span class="btn-text">{{ __('messages.recorder_delete_btn') }}</span>
            </button>
        </div>

        <div class="recorder-status d-flex align-items-center gap-2">
            <div class="recording-indicator" id="recordingIndicator" style="display: none;">
                <div class="pulse-dot"></div>
                <span class="text-danger">{{ __('messages.recorder_status_recording') }}</span>
            </div>

            <div class="timer" id="timer" style="display: none;">
                <span class="text-muted">{{ __('messages.recorder_status_time') }}</span>
                <span id="timeDisplay">00:00</span>
            </div>
        </div>

        <div class="audio-visualizer" id="audioVisualizer" style="display: none;">
            <canvas id="visualizer" width="300" height="60"></canvas>
        </div>

        <div class="audio-player" id="audioPlayer" style="display: none;">
            <audio id="recordedAudio" controls class="w-100">
                {{ __('messages.recorder_audio_unsupported') }}
            </audio>
        </div>

        <input type="hidden" name="voice_note" id="voiceNoteInput">
    </div>
</div>

<style>
.voice-note-recorder {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 2rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}

.voice-note-recorder:hover {
    border-color: #007bff;
    background: #f0f8ff;
}

.recorder-controls .btn {
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

.recorder-controls .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.recording-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pulse-dot {
    width: 12px;
    height: 12px;
    background: #dc3545;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.7;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.timer {
    font-family: 'Courier New', monospace;
    font-weight: bold;
}

.audio-visualizer {
    margin: 1rem 0;
    text-align: center;
}

.audio-visualizer canvas {
    border: 1px solid #dee2e6;
    border-radius: 5px;
    background: #fff;
}

.audio-player {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #fff;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.audio-player audio {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    height: 70px;
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    display: block;
    background: #fff;
    border: 2px solid #e9ecef;
}

.audio-player audio::-webkit-media-controls-panel {
    background-color: #f8f9fa;
}

.audio-player audio::-webkit-media-controls-play-button {
    background-color: #007bff;
    border-radius: 50%;
    margin: 0 10px;
}

.audio-player audio::-webkit-media-controls-current-time-display,
.audio-player audio::-webkit-media-controls-time-remaining-display {
    font-size: 14px;
    font-weight: bold;
    color: #495057;
}

/* تحسين مظهر الأزرار */
.record-btn.recording {
    background: #dc3545;
    border-color: #dc3545;
    color: white;
}

.play-btn.playing {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

/* تأثيرات إضافية */
.recorder-controls .btn:active {
    transform: translateY(0);
}

.recorder-controls .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

<script>
class VoiceRecorder {
    constructor() {
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.audioBlob = null;
        this.audioUrl = null;
        this.isRecording = false;
        this.isPlaying = false;
        this.startTime = 0;
        this.timerInterval = null;

        this.initializeElements();
        this.bindEvents();
    }

    initializeElements() {
        this.recordBtn = document.getElementById('recordBtn');
        this.stopBtn = document.getElementById('stopBtn');
        this.playBtn = document.getElementById('playBtn');
        this.deleteBtn = document.getElementById('deleteBtn');
        this.recordingIndicator = document.getElementById('recordingIndicator');
        this.timer = document.getElementById('timer');
        this.timeDisplay = document.getElementById('timeDisplay');
        this.audioVisualizer = document.getElementById('audioVisualizer');
        this.visualizer = document.getElementById('visualizer');
        this.audioPlayer = document.getElementById('audioPlayer');
        this.recordedAudio = document.getElementById('recordedAudio');
        this.voiceNoteInput = document.getElementById('voiceNoteInput');
    }

    bindEvents() {
        this.recordBtn.addEventListener('click', () => this.startRecording());
        this.stopBtn.addEventListener('click', () => this.stopRecording());
        this.playBtn.addEventListener('click', () => this.playRecording());
        this.deleteBtn.addEventListener('click', () => this.deleteRecording());
    }

    async startRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            this.mediaRecorder = new MediaRecorder(stream);
            this.audioChunks = [];

            this.mediaRecorder.ondataavailable = (event) => {
                this.audioChunks.push(event.data);
            };

            this.mediaRecorder.onstop = () => {
                this.audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                this.audioUrl = URL.createObjectURL(this.audioBlob);
                this.recordedAudio.src = this.audioUrl;

                // تحويل الصوت إلى base64
                const reader = new FileReader();
                reader.onload = () => {
                    this.voiceNoteInput.value = reader.result;
                };
                reader.readAsDataURL(this.audioBlob);

                this.showPlayControls();
                this.stopTimer();
            };

            this.mediaRecorder.start();
            this.isRecording = true;
            this.startTime = Date.now();

            this.showRecordingState();
            this.startTimer();
            this.startVisualizer(stream);

        } catch (error) {
            console.error('خطأ في الوصول للميكروفون:', error);
            alert('لا يمكن الوصول للميكروفون. يرجى السماح بالوصول للميكروفون.');
        }
    }

    stopRecording() {
        if (this.mediaRecorder && this.isRecording) {
            this.mediaRecorder.stop();
            this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
            this.isRecording = false;

            this.hideRecordingState();
            this.stopVisualizer();
        }
    }

    playRecording() {
        if (this.recordedAudio.src) {
            if (this.isPlaying) {
                this.recordedAudio.pause();
                this.isPlaying = false;
                this.playBtn.innerHTML = '<i class="fas fa-play"></i><span class="btn-text">تشغيل</span>';
                this.playBtn.classList.remove('playing');
            } else {
                this.recordedAudio.play();
                this.isPlaying = true;
                this.playBtn.innerHTML = '<i class="fas fa-pause"></i><span class="btn-text">إيقاف مؤقت</span>';
                this.playBtn.classList.add('playing');
            }
        }
    }

    deleteRecording() {
        this.audioBlob = null;
        this.audioUrl = null;
        this.recordedAudio.src = '';
        this.voiceNoteInput.value = '';

        this.hidePlayControls();
        this.hideRecordingState();
        this.stopTimer();
        this.stopVisualizer();

        this.isPlaying = false;
        this.isRecording = false;
    }

    showRecordingState() {
        this.recordBtn.style.display = 'none';
        this.stopBtn.style.display = 'inline-block';
        this.recordingIndicator.style.display = 'flex';
        this.timer.style.display = 'flex';
        this.audioVisualizer.style.display = 'block';

        this.recordBtn.classList.add('recording');
    }

    hideRecordingState() {
        this.recordBtn.style.display = 'inline-block';
        this.stopBtn.style.display = 'none';
        this.recordingIndicator.style.display = 'none';
        this.audioVisualizer.style.display = 'none';

        this.recordBtn.classList.remove('recording');
    }

    showPlayControls() {
        this.playBtn.style.display = 'inline-block';
        this.deleteBtn.style.display = 'inline-block';
        this.audioPlayer.style.display = 'block';
    }

    hidePlayControls() {
        this.playBtn.style.display = 'none';
        this.deleteBtn.style.display = 'none';
        this.audioPlayer.style.display = 'none';
    }

    startTimer() {
        this.timerInterval = setInterval(() => {
            const elapsed = Date.now() - this.startTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            this.timeDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    stopTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    }

    startVisualizer(stream) {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const analyser = audioContext.createAnalyser();
        const microphone = audioContext.createMediaStreamSource(stream);

        microphone.connect(analyser);
        analyser.fftSize = 256;

        const bufferLength = analyser.frequencyBinCount;
        const dataArray = new Uint8Array(bufferLength);

        const canvas = this.visualizer;
        const canvasCtx = canvas.getContext('2d');

        const draw = () => {
            if (!this.isRecording) return;

            requestAnimationFrame(draw);

            analyser.getByteFrequencyData(dataArray);

            canvasCtx.fillStyle = 'rgb(255, 255, 255)';
            canvasCtx.fillRect(0, 0, canvas.width, canvas.height);

            const barWidth = (canvas.width / bufferLength) * 2.5;
            let barHeight;
            let x = 0;

            for (let i = 0; i < bufferLength; i++) {
                barHeight = dataArray[i] / 2;

                canvasCtx.fillStyle = `rgb(${barHeight + 100}, 50, 50)`;
                canvasCtx.fillRect(x, canvas.height - barHeight, barWidth, barHeight);

                x += barWidth + 1;
            }
        };

        draw();
    }

    stopVisualizer() {
        // إيقاف الرسوم المتحركة
    }
}

// تهيئة مسجل الصوت عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    new VoiceRecorder();
});
</script>
