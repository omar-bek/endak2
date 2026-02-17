@if($service->voice_note)
    <div class="voice-note-display mb-3">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-microphone text-primary"></i> تسجيل صوتي
                </h6>
            </div>
            <div class="card-body">
                <audio controls class="w-100">
                    <source src="{{ $service->voice_note }}" type="audio/wav">
                    متصفحك لا يدعم تشغيل الصوت.
                </audio>
            </div>
        </div>
    </div>
@endif

<style>
.voice-note-display .card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.voice-note-display .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
}

.voice-note-display .card-body {
    padding: 1rem;
}

.voice-note-display audio {
    border-radius: 6px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    height: 60px;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    display: block;
    background: #fff;
    border: 2px solid #e9ecef;
}

.voice-note-display audio::-webkit-media-controls-panel {
    background-color: #f8f9fa;
}

.voice-note-display audio::-webkit-media-controls-play-button {
    background-color: #007bff;
    border-radius: 50%;
    margin: 0 8px;
}

.voice-note-display audio::-webkit-media-controls-current-time-display,
.voice-note-display audio::-webkit-media-controls-time-remaining-display {
    font-size: 13px;
    font-weight: bold;
    color: #495057;
}

.voice-note-display h6 {
    color: #495057;
    font-weight: 600;
}

.voice-note-display h6 i {
    margin-left: 0.5rem;
}
</style>
