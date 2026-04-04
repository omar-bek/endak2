{{-- Voice Note Recorder - Modern Design --}}
<div class="vn-recorder" id="vnRecorder">
    <p class="vn-subtitle">{{ __('messages.voice_note_subtitle') }}</p>

    {{-- Idle State --}}
    <div class="vn-state vn-state--idle" id="vnStateIdle">
        <button type="button" class="vn-record-btn" id="vnRecordBtn" aria-label="{{ __('messages.voice_note_tap_to_record') }}">
            <span class="vn-record-btn__ring"></span>
            <span class="vn-record-btn__inner">
                <i class="fas fa-microphone"></i>
            </span>
        </button>
        <span class="vn-status-text">{{ __('messages.voice_note_tap_to_record') }}</span>
    </div>

    {{-- Recording State --}}
    <div class="vn-state vn-state--recording" id="vnStateRecording" style="display:none;">
        <button type="button" class="vn-stop-btn" id="vnStopBtn" aria-label="{{ __('messages.voice_note_tap_to_stop') }}">
            <span class="vn-stop-btn__ring vn-pulse-ring"></span>
            <span class="vn-stop-btn__inner">
                <i class="fas fa-stop"></i>
            </span>
        </button>
        <div class="vn-recording-info">
            <div class="vn-recording-badge">
                <span class="vn-live-dot"></span>
                {{ __('messages.voice_note_recording') }}
            </div>
            <div class="vn-timer" id="vnTimer">00:00</div>
        </div>
        <canvas class="vn-waveform" id="vnWaveform" width="280" height="48"></canvas>
    </div>

    {{-- Recorded State --}}
    <div class="vn-state vn-state--done" id="vnStateDone" style="display:none;">
        <div class="vn-done-badge">
            <i class="fas fa-check-circle"></i>
            {{ __('messages.voice_note_recorded') }}
        </div>
        <div class="vn-player-wrap">
            <audio id="vnAudio" controls class="vn-audio">
                {{ __('messages.recorder_audio_unsupported') }}
            </audio>
        </div>
        <div class="vn-done-actions">
            <button type="button" class="vn-action-btn vn-action-btn--redo" id="vnRedoBtn">
                <i class="fas fa-redo-alt"></i>
                {{ __('messages.voice_note_re_record') }}
            </button>
            <button type="button" class="vn-action-btn vn-action-btn--delete" id="vnDeleteBtn">
                <i class="fas fa-trash-alt"></i>
                {{ __('messages.voice_note_delete') }}
            </button>
        </div>
    </div>

    <input type="hidden" name="voice_note" id="voiceNoteInput">
</div>

<style>
/* ===== Voice Note Recorder ===== */
.vn-recorder {
    text-align: center;
    padding: 0;
}

.vn-subtitle {
    font-size: 0.82rem;
    color: var(--e-gray-400);
    margin-bottom: 1.25rem;
}

/* States */
.vn-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.85rem;
}

/* ---- Idle / Record Button ---- */
.vn-record-btn {
    position: relative;
    width: 72px;
    height: 72px;
    border: none;
    background: none;
    cursor: pointer;
    padding: 0;
    outline: none;
}

.vn-record-btn__ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 2.5px solid var(--e-gray-200);
    transition: all 0.3s ease;
}

.vn-record-btn:hover .vn-record-btn__ring {
    border-color: var(--e-primary);
    transform: scale(1.08);
}

.vn-record-btn__inner {
    position: absolute;
    inset: 8px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--e-primary) 0%, var(--e-primary-dark) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.35rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 14px rgba(47, 92, 105, 0.3);
}

.vn-record-btn:hover .vn-record-btn__inner {
    box-shadow: 0 6px 20px rgba(47, 92, 105, 0.45);
    transform: scale(1.04);
}

.vn-record-btn:active .vn-record-btn__inner {
    transform: scale(0.95);
}

.vn-status-text {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--e-gray-500);
}

/* ---- Recording / Stop Button ---- */
.vn-stop-btn {
    position: relative;
    width: 72px;
    height: 72px;
    border: none;
    background: none;
    cursor: pointer;
    padding: 0;
    outline: none;
}

.vn-stop-btn__ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 2.5px solid var(--e-danger);
}

.vn-pulse-ring {
    animation: vnPulse 1.5s ease-in-out infinite;
}

@keyframes vnPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.12); opacity: 0.6; }
}

.vn-stop-btn__inner {
    position: absolute;
    inset: 8px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--e-danger) 0%, #c0392b 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
    transition: all 0.2s ease;
}

.vn-stop-btn:hover .vn-stop-btn__inner {
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
}

.vn-stop-btn:active .vn-stop-btn__inner {
    transform: scale(0.92);
}

/* Recording info */
.vn-recording-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.vn-recording-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--e-danger);
    background: var(--e-danger-light);
    padding: 0.3rem 0.75rem;
    border-radius: var(--e-radius-full);
}

.vn-live-dot {
    width: 8px;
    height: 8px;
    background: var(--e-danger);
    border-radius: 50%;
    animation: vnBlink 1s step-end infinite;
}

@keyframes vnBlink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

.vn-timer {
    font-family: 'Cairo', 'Courier New', monospace;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--e-gray-800);
    letter-spacing: 1px;
    min-width: 52px;
}

/* Waveform canvas */
.vn-waveform {
    border-radius: var(--e-radius);
    background: var(--e-gray-50);
    border: 1px solid var(--e-gray-100);
    width: 100%;
    max-width: 320px;
    height: 48px;
}

/* ---- Done State ---- */
.vn-done-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--e-success);
    background: var(--e-success-light);
    padding: 0.4rem 1rem;
    border-radius: var(--e-radius-full);
}

.vn-done-badge i {
    font-size: 0.9rem;
}

.vn-player-wrap {
    width: 100%;
    max-width: 420px;
    background: var(--e-gray-50);
    border: 1px solid var(--e-gray-200);
    border-radius: var(--e-radius-lg);
    padding: 0.75rem;
}

.vn-audio {
    width: 100%;
    height: 42px;
    border-radius: var(--e-radius);
    outline: none;
}

.vn-audio::-webkit-media-controls-panel {
    background: var(--e-gray-50);
}

.vn-done-actions {
    display: flex;
    gap: 0.6rem;
}

.vn-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 1rem;
    border: 1.5px solid var(--e-gray-200);
    border-radius: var(--e-radius-full);
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--e-transition);
    background: var(--e-white);
    color: var(--e-gray-600);
}

.vn-action-btn:hover {
    transform: translateY(-1px);
    box-shadow: var(--e-shadow-sm);
}

.vn-action-btn--redo {
    color: var(--e-primary);
    border-color: var(--e-primary-200);
}

.vn-action-btn--redo:hover {
    background: var(--e-primary-50);
    border-color: var(--e-primary);
}

.vn-action-btn--delete {
    color: var(--e-danger);
    border-color: rgba(239, 68, 68, 0.2);
}

.vn-action-btn--delete:hover {
    background: var(--e-danger-light);
    border-color: var(--e-danger);
}

/* ===== Responsive ===== */
@media (max-width: 576px) {
    .vn-record-btn,
    .vn-stop-btn {
        width: 64px;
        height: 64px;
    }

    .vn-record-btn__inner,
    .vn-stop-btn__inner {
        font-size: 1.15rem;
    }

    .vn-waveform {
        max-width: 100%;
    }
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
        this.audioContext = null;
        this.analyser = null;
        this.animFrame = null;

        this.el = {
            stateIdle: document.getElementById('vnStateIdle'),
            stateRecording: document.getElementById('vnStateRecording'),
            stateDone: document.getElementById('vnStateDone'),
            recordBtn: document.getElementById('vnRecordBtn'),
            stopBtn: document.getElementById('vnStopBtn'),
            redoBtn: document.getElementById('vnRedoBtn'),
            deleteBtn: document.getElementById('vnDeleteBtn'),
            timer: document.getElementById('vnTimer'),
            waveform: document.getElementById('vnWaveform'),
            audio: document.getElementById('vnAudio'),
            input: document.getElementById('voiceNoteInput'),
        };

        this.bindEvents();
    }

    bindEvents() {
        this.el.recordBtn.addEventListener('click', () => this.startRecording());
        this.el.stopBtn.addEventListener('click', () => this.stopRecording());
        this.el.redoBtn.addEventListener('click', () => this.deleteAndRestart());
        this.el.deleteBtn.addEventListener('click', () => this.deleteRecording());
    }

    showState(state) {
        this.el.stateIdle.style.display = state === 'idle' ? 'flex' : 'none';
        this.el.stateRecording.style.display = state === 'recording' ? 'flex' : 'none';
        this.el.stateDone.style.display = state === 'done' ? 'flex' : 'none';
    }

    async startRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            this.mediaRecorder = new MediaRecorder(stream);
            this.audioChunks = [];

            this.mediaRecorder.ondataavailable = (e) => {
                this.audioChunks.push(e.data);
            };

            this.mediaRecorder.onstop = () => {
                this.audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                this.audioUrl = URL.createObjectURL(this.audioBlob);
                this.el.audio.src = this.audioUrl;

                const reader = new FileReader();
                reader.onload = () => { this.el.input.value = reader.result; };
                reader.readAsDataURL(this.audioBlob);

                this.showState('done');
                this.stopTimer();
                this.stopWaveform();
            };

            this.mediaRecorder.start();
            this.isRecording = true;
            this.startTime = Date.now();

            this.showState('recording');
            this.startTimer();
            this.startWaveform(stream);

        } catch (err) {
            alert('{{ __("messages.voice_note_mic_error") }}');
        }
    }

    stopRecording() {
        if (this.mediaRecorder && this.isRecording) {
            this.mediaRecorder.stop();
            this.mediaRecorder.stream.getTracks().forEach(t => t.stop());
            this.isRecording = false;
        }
    }

    deleteRecording() {
        this.cleanup();
        this.showState('idle');
    }

    deleteAndRestart() {
        this.cleanup();
        this.startRecording();
    }

    cleanup() {
        this.audioBlob = null;
        this.audioUrl = null;
        this.el.audio.src = '';
        this.el.input.value = '';
        this.isRecording = false;
        this.isPlaying = false;
        this.stopTimer();
        this.stopWaveform();
        this.el.timer.textContent = '00:00';
    }

    startTimer() {
        this.timerInterval = setInterval(() => {
            const elapsed = Date.now() - this.startTime;
            const m = Math.floor(elapsed / 60000);
            const s = Math.floor((elapsed % 60000) / 1000);
            this.el.timer.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }, 1000);
    }

    stopTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    }

    startWaveform(stream) {
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        this.analyser = this.audioContext.createAnalyser();
        const source = this.audioContext.createMediaStreamSource(stream);
        source.connect(this.analyser);
        this.analyser.fftSize = 128;

        const canvas = this.el.waveform;
        const ctx = canvas.getContext('2d');
        const bufLen = this.analyser.frequencyBinCount;
        const data = new Uint8Array(bufLen);

        const primary = getComputedStyle(document.documentElement).getPropertyValue('--e-primary').trim() || '#2f5c69';
        const primaryLight = getComputedStyle(document.documentElement).getPropertyValue('--e-primary-light').trim() || '#3d7a8a';

        const draw = () => {
            if (!this.isRecording) return;
            this.animFrame = requestAnimationFrame(draw);

            this.analyser.getByteFrequencyData(data);

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const barCount = Math.min(bufLen, 40);
            const gap = 3;
            const barW = (canvas.width - (barCount - 1) * gap) / barCount;
            const maxH = canvas.height * 0.85;

            for (let i = 0; i < barCount; i++) {
                const val = data[i] / 255;
                const h = Math.max(3, val * maxH);
                const x = i * (barW + gap);
                const y = (canvas.height - h) / 2;

                const grad = ctx.createLinearGradient(x, y, x, y + h);
                grad.addColorStop(0, primaryLight);
                grad.addColorStop(1, primary);

                ctx.fillStyle = grad;
                ctx.beginPath();
                ctx.roundRect(x, y, barW, h, barW / 2);
                ctx.fill();
            }
        };

        draw();
    }

    stopWaveform() {
        if (this.animFrame) {
            cancelAnimationFrame(this.animFrame);
            this.animFrame = null;
        }
        if (this.audioContext) {
            this.audioContext.close().catch(() => {});
            this.audioContext = null;
        }
        // Clear canvas
        const canvas = this.el.waveform;
        if (canvas) {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('vnRecorder')) {
        new VoiceRecorder();
    }
});
</script>
