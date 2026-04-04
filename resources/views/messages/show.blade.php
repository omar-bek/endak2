@extends('layouts.app')

@section('title', 'المحادثة مع ' . $otherUser->name)

@section('content')
<div class="ec-chat">
    <div class="ec-layout">
        {{-- Sidebar --}}
        <aside class="ec-sidebar" id="conversationsSidebar">
            <div class="ec-sidebar-head">
                <div class="ec-sidebar-title">
                    <i class="fas fa-comments"></i>
                    <span>المحادثات</span>
                </div>
                <button class="ec-sidebar-close" onclick="toggleSidebar()"><i class="fas fa-times"></i></button>
            </div>
            <div class="ec-sidebar-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchConversations" placeholder="بحث...">
            </div>
            <div class="ec-conv-list" id="conversationsList">
                @foreach ($conversations as $conversation)
                    @php
                        $otherUserInList = $conversation->sender_id == auth()->id() ? $conversation->receiver : $conversation->sender;
                        $lastMessage = $conversation;
                        $isActive = $otherUserInList->id == $otherUser->id;
                        $unreadCount = \App\Models\Message::where('conversation_id', $conversation->conversation_id)
                            ->where('receiver_id', auth()->id())->where('is_read', false)->where('is_deleted', false)->count();
                    @endphp
                    <a href="{{ route('messages.show', $otherUserInList->id) }}"
                       class="ec-conv-item {{ $isActive ? 'active' : '' }}"
                       data-name="{{ strtolower($otherUserInList->name) }}">
                        <div class="ec-conv-avatar">
                            @if ($otherUserInList->image && file_exists(public_path('storage/' . $otherUserInList->image)))
                                <img src="{{ asset('storage/' . $otherUserInList->image) }}" alt="{{ $otherUserInList->name }}">
                            @else
                                <div class="ec-avatar-letter">{{ strtoupper(mb_substr($otherUserInList->name, 0, 1)) }}</div>
                            @endif
                            <span class="ec-status-dot {{ $otherUserInList->isOnline() ? 'online' : '' }}"></span>
                        </div>
                        <div class="ec-conv-body">
                            <div class="ec-conv-top">
                                <span class="ec-conv-name">{{ $otherUserInList->name }}</span>
                                @if($lastMessage)
                                    <span class="ec-conv-time">{{ $lastMessage->formatted_time }}</span>
                                @endif
                            </div>
                            <div class="ec-conv-bottom">
                                <span class="ec-conv-preview">
                                    @if ($lastMessage)
                                        @if ($lastMessage->isImage()) <i class="fas fa-image"></i> صورة
                                        @elseif($lastMessage->isVoice()) <i class="fas fa-microphone"></i> رسالة صوتية
                                        @elseif($lastMessage->isFile()) <i class="fas fa-file"></i> ملف
                                        @elseif($lastMessage->isLocation()) <i class="fas fa-map-marker-alt"></i> موقع
                                        @elseif($lastMessage->isContact()) <i class="fas fa-user"></i> جهة اتصال
                                        @else {{ Str::limit($lastMessage->content, 35) }}
                                        @endif
                                    @else لا توجد رسائل
                                    @endif
                                </span>
                                @if ($unreadCount > 0)
                                    <span class="ec-unread">{{ $unreadCount }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </aside>

        {{-- Chat Area --}}
        <main class="ec-main">
            {{-- Header --}}
            <header class="ec-header">
                <button class="ec-menu-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div class="ec-header-user">
                    <div class="ec-header-avatar">
                        @if ($otherUser->image && file_exists(public_path('storage/' . $otherUser->image)))
                            <img src="{{ asset('storage/' . $otherUser->image) }}" alt="{{ $otherUser->name }}">
                        @else
                            <div class="ec-avatar-letter sm">{{ strtoupper(mb_substr($otherUser->name, 0, 1)) }}</div>
                        @endif
                        <span class="ec-status-dot {{ $otherUser->isOnline() ? 'online' : '' }}"></span>
                    </div>
                    <div>
                        <div class="ec-header-name">{{ $otherUser->name }}</div>
                        <div class="ec-header-status">
                            @if ($otherUser->isOnline())
                                <span class="ec-online-text">متصل الآن</span>
                            @else
                                <span class="ec-offline-text">غير متصل</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('messages.index') }}" class="ec-back-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </header>

            {{-- Messages --}}
            <div class="ec-messages" id="chatContent">
                <ul class="ec-msg-list" id="messagesList">
                    @foreach ($messages as $message)
                        @php $isMe = $message->sender_id == auth()->id(); @endphp
                        <li class="ec-msg {{ $isMe ? 'me' : 'them' }}" data-message-id="{{ $message->id }}">
                            <div class="ec-bubble">
                                @if ($isMe)
                                    <button class="ec-msg-del" title="حذف" onclick="deleteMessage({{ $message->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif

                                @if (!empty($message->content))
                                    <div class="ec-msg-text">{{ $message->content }}</div>
                                @endif

                                @if ($message->isImage())
                                    <div class="ec-msg-img" onclick="openImageModal('{{ $message->media_url }}')">
                                        <img src="{{ $message->media_url }}" alt="صورة">
                                    </div>
                                @endif

                                @if ($message->isVoice())
                                    <div class="ec-msg-voice">
                                        <div class="ec-voice-icon"><i class="fas fa-microphone"></i></div>
                                        <audio controls preload="metadata">
                                            <source src="{{ $message->voice_note_url }}" type="audio/wav">
                                            <source src="{{ $message->voice_note_url }}" type="audio/mpeg">
                                        </audio>
                                        @if ($message->getVoiceDuration())
                                            <span class="ec-voice-dur">{{ $message->getVoiceDuration() }}</span>
                                        @endif
                                    </div>
                                @endif

                                @if ($message->isFile())
                                    <a href="{{ $message->media_url }}" download="{{ $message->getFileName() }}" class="ec-msg-file">
                                        <i class="fas fa-file-alt"></i>
                                        <div>
                                            <div class="ec-file-name">{{ $message->getFileName() }}</div>
                                            <div class="ec-file-size">{{ $message->getFileSize() }}</div>
                                        </div>
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif

                                @if ($message->isLocation())
                                    @php $location = $message->getLocationInfo(); @endphp
                                    <a href="https://maps.google.com/?q={{ $location['latitude'] }},{{ $location['longitude'] }}" target="_blank" class="ec-msg-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>عرض الموقع على الخريطة</span>
                                    </a>
                                @endif

                                @if ($message->isContact())
                                    @php $contact = $message->getContactInfo(); @endphp
                                    <div class="ec-msg-contact">
                                        <i class="fas fa-address-card"></i>
                                        <div>
                                            <div class="ec-contact-name">{{ $contact['name'] ?? 'جهة اتصال' }}</div>
                                            @if (isset($contact['phone']))
                                                <div class="ec-contact-phone">{{ $contact['phone'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (empty($message->content) && !$message->isImage() && !$message->isVoice() && !$message->isFile() && !$message->isLocation() && !$message->isContact())
                                    <div class="ec-msg-text">رسالة فارغة</div>
                                @endif

                                <div class="ec-msg-meta">
                                    <span>{{ $message->formatted_time }}</span>
                                    @if ($isMe)
                                        @if ($message->read_at)
                                            <i class="fas fa-check-double read"></i>
                                        @else
                                            <i class="fas fa-check"></i>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Input --}}
            <form action="{{ route('messages.store') }}" method="post" enctype="multipart/form-data" id="messageForm" class="ec-input-bar">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                <input type="hidden" name="conversation_id" value="{{ $conversationId }}">
                <input type="hidden" name="service_id" value="">
                <input type="hidden" name="service_offer_id" value="">
                <input type="hidden" name="voice_note_data" id="voiceNoteInput">

                {{-- Attachments --}}
                <div class="ec-attach-row">
                    <button type="button" id="imageBtn" class="ec-attach-btn green" title="صورة">
                        <i class="fas fa-image"></i>
                    </button>
                    <input type="file" name="media" id="imageInput" style="display:none" accept="image/*">
                    <button type="button" id="voiceBtn" class="ec-attach-btn orange" title="تسجيل صوتي">
                        <i class="fas fa-microphone"></i>
                    </button>
                </div>

                {{-- Image Preview --}}
                <div id="imagePreview" class="ec-img-preview" style="display:none">
                    <img id="imagePreviewImg" src="" alt="">
                    <div class="ec-img-info">
                        <span id="imageName"></span>
                        <span id="imageSize"></span>
                    </div>
                    <button type="button" id="removeImageBtn" class="ec-img-remove"><i class="fas fa-times"></i></button>
                </div>

                {{-- Voice Controls --}}
                <div id="voiceControls" class="ec-voice-controls" style="display:none">
                    <button type="button" id="startVoiceBtn" class="ec-voice-start"><i class="fas fa-circle"></i> تسجيل</button>
                    <button type="button" id="stopVoiceBtn" class="ec-voice-stop" style="display:none"><i class="fas fa-stop"></i> إيقاف</button>
                    <span id="voiceTimer" class="ec-voice-timer">00:00</span>
                    <audio id="voicePlayback" controls style="display:none;flex:1;height:36px;"></audio>
                </div>

                {{-- Text Input --}}
                <div class="ec-input-row">
                    <textarea name="content" rows="1" id="messageInput" class="ec-textarea" placeholder="اكتب رسالتك..."></textarea>
                    <button type="submit" id="sendBtn" class="ec-send-btn" disabled>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="ec-modal" style="display:none">
    <span class="ec-modal-close" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" src="" alt="">
</div>

<style>
/* ===== Chat Redesign ===== */
.ec-chat {
    height: 100vh;
    display: flex;
    flex-direction: column;
    font-family: var(--e-font);
    overflow: hidden;
}
@media (min-width: 1024px) { .ec-chat { margin-top: 62px; height: calc(100vh - 62px); } }

.ec-layout {
    display: flex;
    flex: 1;
    overflow: hidden;
}

/* --- Sidebar --- */
.ec-sidebar {
    width: 320px;
    background: var(--e-white);
    border-left: 1px solid var(--e-gray-200);
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
}
.ec-sidebar-head {
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--e-gray-100);
}
.ec-sidebar-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--e-gray-800);
    display: flex;
    align-items: center;
    gap: 8px;
}
.ec-sidebar-title i { color: var(--e-primary); }
.ec-sidebar-close {
    display: none;
    background: none;
    border: none;
    font-size: 1.1rem;
    color: var(--e-gray-500);
    cursor: pointer;
}
.ec-sidebar-search {
    padding: 10px 14px;
    position: relative;
    border-bottom: 1px solid var(--e-gray-100);
}
.ec-sidebar-search i {
    position: absolute;
    right: 26px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--e-gray-400);
    font-size: 13px;
}
.ec-sidebar-search input {
    width: 100%;
    padding: 9px 36px 9px 14px;
    border: 1.5px solid var(--e-gray-200);
    border-radius: var(--e-radius-full);
    font-family: var(--e-font);
    font-size: 0.85rem;
    background: var(--e-gray-50);
    outline: none;
    transition: var(--e-transition);
}
.ec-sidebar-search input:focus {
    border-color: var(--e-primary);
    background: var(--e-white);
}
.ec-conv-list {
    flex: 1;
    overflow-y: auto;
    padding: 6px 0;
}

/* Conversation Item */
.ec-conv-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    text-decoration: none;
    color: inherit;
    transition: var(--e-transition);
    border-right: 3px solid transparent;
    cursor: pointer;
}
.ec-conv-item:hover { background: var(--e-gray-50); }
.ec-conv-item.active {
    background: var(--e-primary-50);
    border-right-color: var(--e-primary);
}
.ec-conv-avatar {
    position: relative;
    flex-shrink: 0;
}
.ec-conv-avatar img {
    width: 48px;
    height: 48px;
    border-radius: var(--e-radius-full);
    object-fit: cover;
}
.ec-avatar-letter {
    width: 48px;
    height: 48px;
    border-radius: var(--e-radius-full);
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
}
.ec-avatar-letter.sm { width: 42px; height: 42px; font-size: 16px; }
.ec-status-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 12px;
    height: 12px;
    border-radius: var(--e-radius-full);
    background: var(--e-gray-400);
    border: 2px solid var(--e-white);
}
.ec-status-dot.online { background: var(--e-success); }
.ec-conv-body { flex: 1; min-width: 0; }
.ec-conv-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px; }
.ec-conv-name { font-weight: 600; font-size: 0.9rem; color: var(--e-gray-800); }
.ec-conv-time { font-size: 0.72rem; color: var(--e-gray-400); }
.ec-conv-bottom { display: flex; justify-content: space-between; align-items: center; }
.ec-conv-preview {
    font-size: 0.8rem;
    color: var(--e-gray-500);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}
.ec-conv-preview i { margin-left: 4px; font-size: 0.72rem; }
.ec-unread {
    background: var(--e-accent);
    color: var(--e-white);
    font-size: 0.7rem;
    font-weight: 700;
    min-width: 20px;
    height: 20px;
    border-radius: var(--e-radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    margin-right: 4px;
}

/* --- Main Chat --- */
.ec-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: var(--e-gray-50);
    min-width: 0;
}

/* Header */
.ec-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 20px;
    background: var(--e-white);
    border-bottom: 1px solid var(--e-gray-200);
    box-shadow: var(--e-shadow-sm);
    z-index: 2;
}
.ec-menu-btn {
    display: none;
    background: var(--e-primary);
    color: var(--e-white);
    border: none;
    width: 38px;
    height: 38px;
    border-radius: var(--e-radius);
    cursor: pointer;
    font-size: 15px;
    transition: var(--e-transition);
}
.ec-menu-btn:hover { background: var(--e-primary-dark); }
.ec-header-user { display: flex; align-items: center; gap: 12px; flex: 1; }
.ec-header-avatar { position: relative; }
.ec-header-avatar img {
    width: 42px;
    height: 42px;
    border-radius: var(--e-radius-full);
    object-fit: cover;
}
.ec-header-name { font-weight: 700; font-size: 0.95rem; color: var(--e-gray-800); }
.ec-header-status { font-size: 0.75rem; }
.ec-online-text { color: var(--e-success); font-weight: 600; }
.ec-offline-text { color: var(--e-gray-400); }
.ec-back-link {
    width: 38px;
    height: 38px;
    border-radius: var(--e-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--e-gray-500);
    transition: var(--e-transition);
}
.ec-back-link:hover { background: var(--e-gray-100); color: var(--e-primary); }

/* Messages Area */
.ec-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: linear-gradient(180deg, #f0f4f6, #e8eef1);
}
.ec-msg-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* Message Bubble */
.ec-msg {
    display: flex;
    max-width: 75%;
    animation: ecFadeIn 0.25s ease;
}
@keyframes ecFadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

.ec-msg.me { align-self: flex-end; }
.ec-msg.them { align-self: flex-start; }

.ec-bubble {
    position: relative;
    padding: 10px 14px;
    border-radius: 16px;
    max-width: 100%;
    word-wrap: break-word;
    line-height: 1.5;
}
.ec-msg.me .ec-bubble {
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    border-bottom-left: 4px;
    border-radius: 16px 16px 4px 16px;
}
.ec-msg.them .ec-bubble {
    background: var(--e-white);
    color: var(--e-gray-700);
    border: 1px solid var(--e-gray-200);
    border-radius: 16px 16px 16px 4px;
    box-shadow: var(--e-shadow-sm);
}
.ec-msg-text { font-size: 0.9rem; margin-bottom: 2px; }

/* Meta */
.ec-msg-meta {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.7rem;
    margin-top: 4px;
}
.ec-msg.me .ec-msg-meta { justify-content: flex-end; color: rgba(255,255,255,0.7); }
.ec-msg.them .ec-msg-meta { color: var(--e-gray-400); }
.ec-msg-meta .read { color: #7dd3fc; }

/* Delete */
.ec-msg-del {
    display: none;
    position: absolute;
    top: -8px;
    right: -8px;
    width: 26px;
    height: 26px;
    border-radius: var(--e-radius-full);
    background: var(--e-danger);
    color: var(--e-white);
    border: 2px solid var(--e-white);
    font-size: 10px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    box-shadow: var(--e-shadow-sm);
}
.ec-msg:hover .ec-msg-del { display: flex; }

/* Image Message */
.ec-msg-img {
    margin: 6px 0 2px;
    border-radius: 10px;
    overflow: hidden;
    max-width: 280px;
    cursor: pointer;
}
.ec-msg-img img {
    width: 100%;
    display: block;
    transition: var(--e-transition);
}
.ec-msg-img:hover img { transform: scale(1.02); }

/* Voice */
.ec-msg-voice {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 6px 0 2px;
    min-width: 200px;
}
.ec-voice-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--e-radius-full);
    background: var(--e-accent);
    color: var(--e-white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}
.ec-msg-voice audio { flex: 1; height: 36px; border-radius: 18px; }
.ec-voice-dur { font-size: 0.72rem; opacity: 0.7; }

/* File */
.ec-msg-file {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: var(--e-radius);
    text-decoration: none;
    color: inherit;
    margin: 6px 0 2px;
    transition: var(--e-transition);
}
.ec-msg.me .ec-msg-file { background: rgba(255,255,255,0.15); color: var(--e-white); }
.ec-msg.them .ec-msg-file { background: var(--e-gray-50); border: 1px solid var(--e-gray-200); }
.ec-msg-file:hover { opacity: 0.85; }
.ec-msg-file > i:first-child { font-size: 1.3rem; }
.ec-msg-file > div { flex: 1; }
.ec-file-name { font-size: 0.82rem; font-weight: 600; }
.ec-file-size { font-size: 0.72rem; opacity: 0.7; }
.ec-msg-file > i:last-child { font-size: 0.85rem; opacity: 0.6; }

/* Location */
.ec-msg-location {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: var(--e-radius);
    text-decoration: none;
    margin: 6px 0 2px;
    font-size: 0.85rem;
    font-weight: 500;
}
.ec-msg.me .ec-msg-location { background: rgba(255,255,255,0.15); color: var(--e-white); }
.ec-msg.them .ec-msg-location { background: var(--e-info-light); color: var(--e-info); }

/* Contact */
.ec-msg-contact {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: var(--e-radius);
    margin: 6px 0 2px;
}
.ec-msg.me .ec-msg-contact { background: rgba(255,255,255,0.15); }
.ec-msg.them .ec-msg-contact { background: var(--e-gray-50); border: 1px solid var(--e-gray-200); }
.ec-contact-name { font-size: 0.85rem; font-weight: 600; }
.ec-contact-phone { font-size: 0.78rem; opacity: 0.7; }

/* --- Input Bar --- */
.ec-input-bar {
    background: var(--e-white);
    border-top: 1px solid var(--e-gray-200);
    padding: 12px 16px;
}
.ec-attach-row {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}
.ec-attach-btn {
    width: 38px;
    height: 38px;
    border-radius: var(--e-radius-full);
    border: none;
    color: var(--e-white);
    font-size: 14px;
    cursor: pointer;
    transition: var(--e-transition);
    display: flex;
    align-items: center;
    justify-content: center;
}
.ec-attach-btn:hover { transform: scale(1.08); }
.ec-attach-btn.green { background: var(--e-success); }
.ec-attach-btn.orange { background: var(--e-accent); }

.ec-input-row {
    display: flex;
    align-items: flex-end;
    gap: 10px;
}
.ec-textarea {
    flex: 1;
    border: 1.5px solid var(--e-gray-200);
    border-radius: 22px;
    padding: 10px 18px;
    font-family: var(--e-font);
    font-size: 0.9rem;
    resize: none;
    outline: none;
    max-height: 120px;
    line-height: 1.5;
    transition: var(--e-transition);
    background: var(--e-gray-50);
}
.ec-textarea:focus {
    border-color: var(--e-primary);
    background: var(--e-white);
    box-shadow: 0 0 0 3px var(--e-primary-100);
}
.ec-send-btn {
    width: 44px;
    height: 44px;
    border-radius: var(--e-radius-full);
    border: none;
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    font-size: 16px;
    cursor: pointer;
    transition: var(--e-transition);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: var(--e-shadow-primary);
}
.ec-send-btn:hover:not(:disabled) { transform: scale(1.08); }
.ec-send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

/* Image Preview */
.ec-img-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    background: var(--e-success-light);
    border-radius: var(--e-radius);
    margin-bottom: 8px;
}
.ec-img-preview img {
    width: 56px;
    height: 56px;
    border-radius: var(--e-radius);
    object-fit: cover;
    border: 2px solid var(--e-white);
}
.ec-img-info { flex: 1; }
.ec-img-info span { display: block; font-size: 0.8rem; color: #065f46; }
.ec-img-info span:last-child { font-size: 0.72rem; opacity: 0.7; }
.ec-img-remove {
    width: 28px;
    height: 28px;
    border-radius: var(--e-radius-full);
    background: var(--e-danger);
    color: var(--e-white);
    border: none;
    cursor: pointer;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Voice Controls */
.ec-voice-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: var(--e-warning-light);
    border-radius: var(--e-radius);
    margin-bottom: 8px;
}
.ec-voice-start, .ec-voice-stop {
    padding: 6px 16px;
    border-radius: var(--e-radius-full);
    border: none;
    font-family: var(--e-font);
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ec-voice-start { background: var(--e-danger); color: var(--e-white); }
.ec-voice-stop { background: var(--e-gray-600); color: var(--e-white); }
.ec-voice-timer { font-weight: 700; font-size: 0.9rem; color: var(--e-gray-700); }

/* Modal */
.ec-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,0.92);
    display: flex;
    align-items: center;
    justify-content: center;
    animation: ecFadeIn 0.2s ease;
}
.ec-modal img {
    max-width: 92%;
    max-height: 90vh;
    border-radius: var(--e-radius);
    box-shadow: 0 8px 40px rgba(0,0,0,0.5);
}
.ec-modal-close {
    position: absolute;
    top: 20px;
    right: 24px;
    color: var(--e-white);
    font-size: 2.2rem;
    cursor: pointer;
    transition: var(--e-transition);
    line-height: 1;
}
.ec-modal-close:hover { color: var(--e-danger); }

/* --- Responsive --- */
@media (max-width: 768px) {
    .ec-sidebar {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        height: 100%;
        z-index: 999;
        transition: right 0.35s ease;
    }
    .ec-sidebar.active { right: 0; }
    .ec-sidebar-close { display: block; }
    .ec-menu-btn { display: flex; }
    .ec-msg { max-width: 88%; }
    .ec-back-link { display: none; }
}

/* Scrollbar */
.ec-messages::-webkit-scrollbar,
.ec-conv-list::-webkit-scrollbar { width: 5px; }
.ec-messages::-webkit-scrollbar-thumb,
.ec-conv-list::-webkit-scrollbar-thumb {
    background: var(--e-gray-300);
    border-radius: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const imageBtn = document.getElementById('imageBtn');
    const imageInput = document.getElementById('imageInput');
    const voiceBtn = document.getElementById('voiceBtn');
    const voiceControls = document.getElementById('voiceControls');
    const startVoiceBtn = document.getElementById('startVoiceBtn');
    const stopVoiceBtn = document.getElementById('stopVoiceBtn');
    const voiceTimer = document.getElementById('voiceTimer');
    const voicePlayback = document.getElementById('voicePlayback');
    const voiceNoteInput = document.getElementById('voiceNoteInput');
    const sendBtn = document.getElementById('sendBtn');

    let mediaRecorder = null;
    let audioChunks = [];
    let recordingInterval = null;
    let recordingSeconds = 0;

    // Image upload
    if (imageBtn && imageInput) {
        imageBtn.addEventListener('click', () => imageInput.click());
        imageInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                displayImagePreview(this.files[0]);
                updateSendButtonState();
            }
        });
    }

    function displayImagePreview(file) {
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('imagePreviewImg');
        const name = document.getElementById('imageName');
        const size = document.getElementById('imageSize');

        name.textContent = file.name;
        size.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; };
        reader.readAsDataURL(file);
        preview.style.display = 'flex';

        document.getElementById('removeImageBtn').onclick = () => {
            preview.style.display = 'none';
            imageInput.value = '';
            updateSendButtonState();
        };
    }

    // Voice recording
    if (voiceBtn && voiceControls) {
        voiceBtn.addEventListener('click', () => {
            voiceControls.style.display = voiceControls.style.display === 'none' ? 'flex' : 'none';
        });
    }

    if (startVoiceBtn) startVoiceBtn.addEventListener('click', startVoiceRecording);
    if (stopVoiceBtn) stopVoiceBtn.addEventListener('click', stopVoiceRecording);

    function startVoiceRecording() {
        navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            recordingSeconds = 0;
            mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
            mediaRecorder.onstop = () => {
                const blob = new Blob(audioChunks, { type: 'audio/wav' });
                const reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = () => {
                    voiceNoteInput.value = reader.result;
                    voicePlayback.src = URL.createObjectURL(blob);
                    voicePlayback.style.display = 'block';
                    updateSendButtonState();
                };
                stream.getTracks().forEach(t => t.stop());
            };
            mediaRecorder.start();
            startVoiceBtn.style.display = 'none';
            stopVoiceBtn.style.display = 'inline-flex';
            recordingInterval = setInterval(() => {
                recordingSeconds++;
                const m = Math.floor(recordingSeconds / 60), s = recordingSeconds % 60;
                voiceTimer.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
            }, 1000);
        })
        .catch(() => alert('لا يمكن الوصول إلى الميكروفون.'));
    }

    function stopVoiceRecording() {
        if (mediaRecorder && mediaRecorder.state === 'recording') mediaRecorder.stop();
        if (recordingInterval) { clearInterval(recordingInterval); recordingInterval = null; }
        startVoiceBtn.style.display = 'inline-flex';
        stopVoiceBtn.style.display = 'none';
        voiceTimer.textContent = '00:00';
        recordingSeconds = 0;
    }

    // State
    if (messageInput) messageInput.addEventListener('input', updateSendButtonState);

    function updateSendButtonState() {
        const has = (messageInput && messageInput.value.trim()) ||
                    (voiceNoteInput && voiceNoteInput.value) ||
                    (imageInput && imageInput.files.length > 0);
        if (sendBtn) sendBtn.disabled = !has;
    }

    // Submit
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const hasContent = (messageInput && messageInput.value.trim()) ||
                               (voiceNoteInput && voiceNoteInput.value) ||
                               (imageInput && imageInput.files.length > 0);
            if (!hasContent) return;

            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(messageForm.action, {
                method: 'POST',
                body: new FormData(messageForm),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const list = document.getElementById('messagesList');
                    if (list && data.html) {
                        list.insertAdjacentHTML('beforeend', data.html);
                        if (data.message && data.message.id) lastMessageId = data.message.id;
                        else {
                            const last = list.querySelector('.message-item:last-child, .ec-msg:last-child');
                            if (last) { const mid = last.getAttribute('data-message-id'); if (mid && parseInt(mid) > lastMessageId) lastMessageId = parseInt(mid); }
                        }
                    }
                    const chat = document.getElementById('chatContent');
                    if (chat) setTimeout(() => chat.scrollTop = chat.scrollHeight, 100);
                    if (messageInput) messageInput.value = '';
                    if (voiceNoteInput) voiceNoteInput.value = '';
                    if (imageInput) imageInput.value = '';
                    if (voicePlayback) voicePlayback.style.display = 'none';
                    document.getElementById('imagePreview').style.display = 'none';
                    if (voiceControls) voiceControls.style.display = 'none';
                    if (startVoiceBtn) startVoiceBtn.style.display = 'inline-flex';
                    if (stopVoiceBtn) stopVoiceBtn.style.display = 'none';
                    if (voiceTimer) voiceTimer.textContent = '00:00';
                    updateSendButtonState();
                } else {
                    alert('فشل في إرسال الرسالة: ' + (data.message || 'خطأ'));
                }
            })
            .catch(() => alert('حدث خطأ أثناء إرسال الرسالة'))
            .finally(() => {
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            });
        });
    }

    // Auto-resize
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }

    updateSendButtonState();

    // Polling
    let lastMessageId = {{ $messages->max('id') ?? 0 }};
    const conversationId = '{{ $conversationId }}';
    let isPolling = true;

    function fetchNewMessages() {
        if (!isPolling) return;
        const params = new URLSearchParams({ conversation_id: conversationId, last_message_id: lastMessageId });
        fetch('{{ route('messages.new') }}?' + params, {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.messages && data.messages.length > 0) {
                const list = document.getElementById('messagesList');
                const chat = document.getElementById('chatContent');
                const wasBottom = chat ? (chat.scrollHeight - chat.scrollTop - chat.clientHeight < 100) : true;
                if (data.html && list) {
                    const tmp = document.createElement('div');
                    tmp.innerHTML = data.html;
                    tmp.querySelectorAll('.message-item').forEach(el => {
                        const mid = el.getAttribute('data-message-id');
                        if (mid && parseInt(mid) > lastMessageId && !list.querySelector(`[data-message-id="${mid}"]`)) {
                            list.appendChild(el.cloneNode(true));
                        }
                    });
                }
                if (data.messages.length > 0) {
                    const maxId = Math.max(...data.messages.map(m => m.id));
                    if (maxId > lastMessageId) lastMessageId = maxId;
                }
                if (wasBottom && chat) setTimeout(() => chat.scrollTop = chat.scrollHeight, 100);
            }
        })
        .catch(() => {});
    }

    setInterval(fetchNewMessages, 3000);

    // Search
    const searchInput = document.getElementById('searchConversations');
    const convItems = document.querySelectorAll('.ec-conv-item');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            convItems.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                item.style.display = name.includes(q) ? 'flex' : 'none';
            });
        });
    }

    // Scroll to bottom
    const chatContent = document.getElementById('chatContent');
    if (chatContent) chatContent.scrollTop = chatContent.scrollHeight;
});

function deleteMessage(id) {
    if (!confirm('هل أنت متأكد من حذف هذه الرسالة؟')) return;
    fetch(`/messages/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
    })
    .then(r => { if (r.ok) document.querySelector(`[data-message-id="${id}"]`)?.remove(); else alert('فشل في حذف الرسالة'); })
    .catch(() => alert('حدث خطأ'));
}

function toggleSidebar() {
    document.getElementById('conversationsSidebar').classList.toggle('active');
}

function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    document.getElementById('modalImage').src = src;
    modal.style.display = 'flex';
    modal.onclick = e => { if (e.target === modal) closeImageModal(); };
}
function closeImageModal() { document.getElementById('imageModal').style.display = 'none'; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeImageModal(); });
</script>
@endsection
