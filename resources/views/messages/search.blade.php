@extends('layouts.app')

@section('title', 'البحث في الرسائل')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- العنوان -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">البحث في الرسائل</h1>
            <p class="text-gray-600">ابحث في رسائلك ومحادثاتك</p>
        </div>

        <!-- شريط البحث -->
        <div class="mb-6">
            <form action="{{ route('messages.search') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text"
                           name="q"
                           placeholder="ابحث في الرسائل..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ $query }}">
                </div>
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search ml-2"></i>
                    بحث
                </button>
            </form>
        </div>

        <!-- نتائج البحث -->
        @if($query)
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    نتائج البحث عن "{{ $query }}"
                    <span class="text-sm font-normal text-gray-500">({{ $messages->total() }} نتيجة)</span>
                </h2>
            </div>
        @endif

        <!-- قائمة الرسائل -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            @if($messages->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($messages as $message)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-4 space-x-reverse">
                                <!-- صورة المستخدم -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($message->sender->name, 0, 1) }}
                                    </div>
                                </div>

                                <!-- محتوى الرسالة -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <span class="font-semibold text-gray-900">
                                                {{ $message->sender->name }}
                                            </span>
                                            <span class="text-sm text-gray-500">إلى</span>
                                            <span class="font-semibold text-gray-900">
                                                {{ $message->receiver->name }}
                                            </span>
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            {{ $message->formatted_time }}
                                        </span>
                                    </div>

                                    <!-- محتوى الرسالة -->
                                    <div class="mb-3">
                                        @if($message->isText())
                                            <p class="text-gray-700">
                                                {{ Str::limit($message->content, 200) }}
                                            </p>
                                        @elseif($message->isImage())
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <i class="fas fa-image text-blue-500"></i>
                                                <span class="text-gray-700">صورة</span>
                                                @if($message->content)
                                                    <span class="text-gray-500">-</span>
                                                    <span class="text-gray-700">{{ Str::limit($message->content, 100) }}</span>
                                                @endif
                                            </div>
                                        @elseif($message->isVoice())
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <i class="fas fa-microphone text-green-500"></i>
                                                <span class="text-gray-700">رسالة صوتية</span>
                                                @if($message->content)
                                                    <span class="text-gray-500">-</span>
                                                    <span class="text-gray-700">{{ Str::limit($message->content, 100) }}</span>
                                                @endif
                                            </div>
                                        @elseif($message->isFile())
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <i class="fas fa-file text-orange-500"></i>
                                                <span class="text-gray-700">ملف: {{ $message->getFileName() }}</span>
                                                @if($message->content)
                                                    <span class="text-gray-500">-</span>
                                                    <span class="text-gray-700">{{ Str::limit($message->content, 100) }}</span>
                                                @endif
                                            </div>
                                        @elseif($message->isLocation())
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <i class="fas fa-map-marker-alt text-red-500"></i>
                                                <span class="text-gray-700">موقع</span>
                                                @if($message->content)
                                                    <span class="text-gray-500">-</span>
                                                    <span class="text-gray-700">{{ Str::limit($message->content, 100) }}</span>
                                                @endif
                                            </div>
                                        @elseif($message->isContact())
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <i class="fas fa-user text-purple-500"></i>
                                                <span class="text-gray-700">معلومات اتصال</span>
                                                @if($message->content)
                                                    <span class="text-gray-500">-</span>
                                                    <span class="text-gray-700">{{ Str::limit($message->content, 100) }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- أزرار الإجراءات -->
                                    <div class="flex items-center space-x-3 space-x-reverse">
                                        <a href="{{ route('messages.show', $message->sender_id === auth()->id() ? $message->receiver_id : $message->sender_id) }}"
                                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-comments ml-1"></i>
                                            عرض المحادثة
                                        </a>

                                        @if($message->is_own_message)
                                            <button onclick="deleteMessage({{ $message->id }})"
                                                    class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                                <i class="fas fa-trash ml-1"></i>
                                                حذف
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- الترقيم -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $messages->appends(['q' => $query])->links() }}
                </div>
            @else
                <!-- حالة فارغة -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-gray-400"></i>
                    </div>
                    @if($query)
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">لا توجد نتائج</h3>
                        <p class="text-gray-500 mb-6">لم نتمكن من العثور على رسائل تطابق "{{ $query }}"</p>
                    @else
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">ابدأ البحث</h3>
                        <p class="text-gray-500 mb-6">اكتب كلمة أو عبارة للبحث في رسائلك</p>
                    @endif
                    <a href="{{ route('messages.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للرسائل
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// حذف رسالة
function deleteMessage(messageId) {
    if (confirm('هل أنت متأكد من حذف هذه الرسالة؟')) {
        fetch(`/messages/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // إعادة تحميل الصفحة
                location.reload();
            } else {
                alert('حدث خطأ في حذف الرسالة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في حذف الرسالة');
        });
    }
}
</script>
@endpush

@push('styles')
<style>
/* تحسينات إضافية للتصميم */
.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}

.transition-colors {
    transition: all 0.2s ease-in-out;
}

/* تأثيرات بصرية */
.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>
@endpush
@endsection
