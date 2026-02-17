@php
    $isCurrentUser = $message->sender_id == auth()->id();
    $sender = $message->sender;
@endphp
<li class="message-item {{ $isCurrentUser ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
    <div class="message-content">
        @if($isCurrentUser)
            <div class="message-actions">
                <button class="message-delete-btn" title="حذف الرسالة" onclick="deleteMessage({{ $message->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        @endif

        @if(!empty($message->content))
            <div class="message-text">{{ $message->content }}</div>
        @endif

        @if($message->isImage())
            <div class="image-message">
                <img src="{{ $message->media_url }}" alt="صورة الرسالة"
                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.style.display='block';"
                     style="max-width: 300px; max-height: 300px; border-radius: 10px; cursor: pointer;"
                     onclick="openImageModal('{{ $message->media_url }}')">
            </div>
        @endif

        @if($message->isVoice())
            <div class="audio-message">
                <div class="voice-message-container">
                    <div class="voice-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <audio controls preload="metadata" style="flex: 1; margin: 0 10px;">
                        <source src="{{ $message->voice_note_url }}" type="audio/wav">
                        <source src="{{ $message->voice_note_url }}" type="audio/mpeg">
                        <source src="{{ $message->voice_note_url }}" type="audio/ogg">
                        متصفحك لا يدعم عنصر الصوت.
                    </audio>
                    @if($message->getVoiceDuration())
                        <div class="voice-duration">{{ $message->getVoiceDuration() }}</div>
                    @endif
                </div>
            </div>
        @endif

        @if($message->isFile())
            <div class="file-message">
                <a href="{{ $message->media_url }}" download="{{ $message->getFileName() }}" class="file-link">
                    <i class="fas fa-file"></i>
                    <span>{{ $message->getFileName() }}</span>
                    <small>{{ $message->getFileSize() }}</small>
                </a>
            </div>
        @endif

        @if($message->isLocation())
            @php $location = $message->getLocationInfo(); @endphp
            <div class="location-message">
                <div class="location-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>الموقع</span>
                </div>
                <a href="https://maps.google.com/?q={{ $location['latitude'] }},{{ $location['longitude'] }}" target="_blank" class="location-link">
                    عرض على الخريطة
                </a>
            </div>
        @endif

        @if($message->isContact())
            @php $contact = $message->getContactInfo(); @endphp
            <div class="contact-message">
                <div class="contact-info">
                    <i class="fas fa-user"></i>
                    <span>{{ $contact['name'] ?? 'معلومات الاتصال' }}</span>
                </div>
                @if(isset($contact['phone']))
                    <div class="contact-phone">
                        <i class="fas fa-phone"></i>
                        <span>{{ $contact['phone'] }}</span>
                    </div>
                @endif
            </div>
        @endif

        @if(empty($message->content) && !$message->isImage() && !$message->isVoice() && !$message->isFile() && !$message->isLocation() && !$message->isContact())
            <div class="message-text">رسالة فارغة</div>
        @endif

        <div class="message-meta">
            <span>{{ $message->formatted_time }}</span>
            @if($isCurrentUser)
                @if($message->read_at)
                    <i class="fas fa-check-double text-info" title="مقروءة في {{ $message->read_at->format('h:i A') }}"></i>
                @else
                    <i class="fas fa-check" title="مرسلة"></i>
                @endif
            @endif
        </div>
    </div>
</li>
