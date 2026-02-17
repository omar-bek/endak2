@extends('layouts.admin')

@section('title', 'إدارة المحادثات')
@section('page-title', 'إدارة المحادثات')

@section('content')
<div class="row mb-4">
    <!-- إحصائيات -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_messages'] }}</h3>
                        <p class="text-muted mb-0">إجمالي الرسائل</p>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-comments fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0 text-warning">{{ $stats['unread_messages'] }}</h3>
                        <p class="text-muted mb-0">رسائل غير مقروءة</p>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0 text-info">{{ $stats['text_messages'] }}</h3>
                        <p class="text-muted mb-0">رسائل نصية</p>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0 text-success">{{ $stats['image_messages'] + $stats['voice_messages'] + $stats['file_messages'] }}</h3>
                        <p class="text-muted mb-0">وسائط</p>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-images fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- فلترة وبحث -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">البحث</label>
                <input type="text" name="search" class="form-control" placeholder="بحث في الرسائل..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">نوع الرسالة</label>
                <select name="message_type" class="form-select">
                    <option value="">الكل</option>
                    <option value="text" {{ request('message_type') == 'text' ? 'selected' : '' }}>نص</option>
                    <option value="image" {{ request('message_type') == 'image' ? 'selected' : '' }}>صورة</option>
                    <option value="voice" {{ request('message_type') == 'voice' ? 'selected' : '' }}>صوت</option>
                    <option value="file" {{ request('message_type') == 'file' ? 'selected' : '' }}>ملف</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">حالة القراءة</label>
                <select name="is_read" class="form-select">
                    <option value="">الكل</option>
                    <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>غير مقروء</option>
                    <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>مقروء</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">من تاريخ</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">إلى تاريخ</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- جدول الرسائل -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">جميع الرسائل</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>المرسل</th>
                        <th>المستقبل</th>
                        <th>الرسالة</th>
                        <th>النوع</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
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
                                    <div>
                                        <div class="fw-bold">{{ $message->sender->name }}</div>
                                        <small class="text-muted">{{ $message->sender->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($message->receiver->avatar)
                                        <img src="{{ asset('storage/' . $message->receiver->avatar) }}" 
                                             alt="{{ $message->receiver->name }}" 
                                             class="rounded-circle me-2" 
                                             style="width: 35px; height: 35px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2" 
                                             style="width: 35px; height: 35px;">
                                            {{ strtoupper(substr($message->receiver->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $message->receiver->name }}</div>
                                        <small class="text-muted">{{ $message->receiver->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($message->message_type == 'text')
                                    <div class="text-truncate" style="max-width: 200px;">{{ $message->content }}</div>
                                @elseif($message->message_type == 'image')
                                    <span class="badge bg-info"><i class="fas fa-image"></i> صورة</span>
                                @elseif($message->message_type == 'voice')
                                    <span class="badge bg-warning"><i class="fas fa-microphone"></i> رسالة صوتية</span>
                                @elseif($message->message_type == 'file')
                                    <span class="badge bg-secondary"><i class="fas fa-file"></i> ملف</span>
                                @else
                                    <span class="badge bg-dark">{{ $message->message_type }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $message->message_type == 'text' ? 'primary' : ($message->message_type == 'image' ? 'info' : ($message->message_type == 'voice' ? 'warning' : 'secondary')) }}">
                                    {{ $message->message_type }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $message->created_at->format('Y-m-d H:i') }}</small>
                            </td>
                            <td>
                                @if($message->is_read)
                                    <span class="badge bg-success">مقروء</span>
                                @else
                                    <span class="badge bg-warning">غير مقروء</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.messages.show', $message->conversation_id) }}" 
                                       class="btn btn-info" 
                                       title="عرض المحادثة">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.messages.destroy', $message) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">لا توجد رسائل</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($messages->hasPages())
        <div class="card-footer bg-white">
            {{ $messages->links() }}
        </div>
    @endif
</div>

<!-- المحادثات النشطة -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">المحادثات النشطة</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($activeConversations as $conversation)
                <div class="col-md-6 mb-3">
                    <div class="card border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">محادثة: {{ $conversation->conversation_id }}</h6>
                                    <small class="text-muted">
                                        {{ $conversation->message_count }} رسالة
                                        | آخر رسالة: {{ \Carbon\Carbon::parse($conversation->last_message_at)->diffForHumans() }}
                                    </small>
                                </div>
                                <a href="{{ route('admin.messages.show', $conversation->conversation_id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">لا توجد محادثات نشطة</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

