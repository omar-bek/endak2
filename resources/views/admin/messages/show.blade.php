@extends('layouts.admin')

@section('title', 'عرض المحادثة')
@section('page-title', 'عرض المحادثة')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">معلومات المحادثة</h5>
                    <small class="text-muted">معرف المحادثة: {{ $conversationId }}</small>
                </div>
                <div>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                    <form action="{{ route('admin.messages.destroy-conversation', $conversationId) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه المحادثة بالكامل؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> حذف المحادثة
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>المرسل:</h6>
                        <div class="d-flex align-items-center mb-3">
                            @if($participants['sender']->avatar)
                                <img src="{{ asset('storage/' . $participants['sender']->avatar) }}" 
                                     alt="{{ $participants['sender']->name }}" 
                                     class="rounded-circle me-2" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                     style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    {{ strtoupper(substr($participants['sender']->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $participants['sender']->name }}</div>
                                <small class="text-muted">{{ $participants['sender']->email }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>المستقبل:</h6>
                        <div class="d-flex align-items-center mb-3">
                            @if($participants['receiver']->avatar)
                                <img src="{{ asset('storage/' . $participants['receiver']->avatar) }}" 
                                     alt="{{ $participants['receiver']->name }}" 
                                     class="rounded-circle me-2" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2" 
                                     style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    {{ strtoupper(substr($participants['receiver']->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $participants['receiver']->name }}</div>
                                <small class="text-muted">{{ $participants['receiver']->email }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الرسائل -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">الرسائل ({{ $messages->count() }})</h5>
    </div>
    <div class="card-body" style="max-height: 600px; overflow-y: auto;">
        @forelse($messages as $message)
            <div class="message-item mb-4 {{ $message->sender_id == $participants['sender']->id ? 'text-end' : 'text-start' }}">
                <div class="d-flex {{ $message->sender_id == $participants['sender']->id ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="message-content" style="max-width: 70%;">
                        <div class="d-flex align-items-center mb-2 {{ $message->sender_id == $participants['sender']->id ? 'justify-content-end' : 'justify-content-start' }}">
                            @if($message->sender_id != $participants['sender']->id)
                                @if($message->sender->avatar)
                                    <img src="{{ asset('storage/' . $message->sender->avatar) }}" 
                                         alt="{{ $message->sender->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 35px; height: 35px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 35px; height: 35px;">
                                        {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                    </div>
                                @endif
                            @endif
                            <div>
                                <div class="fw-bold">{{ $message->sender->name }}</div>
                                <small class="text-muted">{{ $message->created_at->format('Y-m-d H:i') }}</small>
                            </div>
                            @if($message->sender_id == $participants['sender']->id)
                                @if($message->sender->avatar)
                                    <img src="{{ asset('storage/' . $message->sender->avatar) }}" 
                                         alt="{{ $message->sender->name }}" 
                                         class="rounded-circle ms-2" 
                                         style="width: 35px; height: 35px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center ms-2" 
                                         style="width: 35px; height: 35px;">
                                        {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        <div class="card border {{ $message->sender_id == $participants['sender']->id ? 'bg-primary text-white' : 'bg-light' }}">
                            <div class="card-body">
                                @if($message->message_type == 'text')
                                    <p class="mb-0">{{ $message->content }}</p>
                                @elseif($message->message_type == 'image')
                                    <div>
                                        <i class="fas fa-image me-2"></i>
                                        <span>صورة</span>
                                        @if($message->media_path)
                                            <br>
                                            <img src="{{ asset('storage/' . $message->media_path) }}" 
                                                 alt="صورة" 
                                                 class="img-thumbnail mt-2" 
                                                 style="max-width: 200px;">
                                        @endif
                                    </div>
                                @elseif($message->message_type == 'voice')
                                    <div>
                                        <i class="fas fa-microphone me-2"></i>
                                        <span>رسالة صوتية</span>
                                        @if($message->voice_note_path)
                                            <br>
                                            <audio controls class="mt-2">
                                                <source src="{{ asset('storage/' . $message->voice_note_path) }}" type="audio/wav">
                                                المتصفح لا يدعم تشغيل الصوت
                                            </audio>
                                        @endif
                                    </div>
                                @elseif($message->message_type == 'file')
                                    <div>
                                        <i class="fas fa-file me-2"></i>
                                        <span>ملف: {{ $message->file_name ?? 'ملف' }}</span>
                                        @if($message->media_path)
                                            <br>
                                            <a href="{{ asset('storage/' . $message->media_path) }}" 
                                               download 
                                               class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-download"></i> تحميل
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <p class="mb-0">{{ $message->content ?? 'رسالة' }}</p>
                                @endif
                                
                                @if($message->service)
                                    <div class="mt-2 pt-2 border-top">
                                        <small>
                                            <i class="fas fa-link"></i> 
                                            مرتبطة بخدمة: 
                                            <a href="{{ route('admin.services.show', $message->service->id) }}" 
                                               class="{{ $message->sender_id == $participants['sender']->id ? 'text-white' : 'text-primary' }}">
                                                {{ $message->service->title }}
                                            </a>
                                        </small>
                                    </div>
                                @endif
                                
                                @if($message->serviceOffer)
                                    <div class="mt-2 pt-2 border-top">
                                        <small>
                                            <i class="fas fa-handshake"></i> 
                                            مرتبطة بعرض: 
                                            <a href="{{ route('admin.service-offers.show', $message->serviceOffer->id) }}" 
                                               class="{{ $message->sender_id == $participants['sender']->id ? 'text-white' : 'text-primary' }}">
                                                عرض #{{ $message->serviceOffer->id }}
                                            </a>
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                @if($message->is_read)
                                    <span class="badge bg-success">مقروء</span>
                                @else
                                    <span class="badge bg-warning">غير مقروء</span>
                                @endif
                                <span class="badge bg-secondary">{{ $message->message_type }}</span>
                            </div>
                            <form action="{{ route('admin.messages.destroy', $message) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد رسائل في هذه المحادثة</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

