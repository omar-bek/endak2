@extends('layouts.app')

@section('title', 'تقديم عرض - ' . $service->title)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- معلومات الخدمة -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-start mb-3">
                        <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> العودة للخدمة
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="img-fluid rounded">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $service->title }}</h4>
                            <p class="text-muted">{{ $service->description }}</p>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user text-primary me-2"></i>
                                <span>{{ $service->user->name }}</span>
                            </div>
                            @if($service->location)
                                <div class="d-flex align-items-center mt-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <span>{{ $service->location }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- نموذج تقديم العرض -->
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-handshake"></i> تقديم عرض للخدمة
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('service-offers.store', $service) }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- السعر -->
                        <div class="mb-3">
                            <label for="price" class="form-label">
                                <i class="fas fa-money-bill-wave text-success"></i> السعر المطلوب *
                            </label>
                            <div class="input-group">
                                <input type="number" name="price" id="price" class="form-control"
                                       value="{{ old('price') }}" step="0.01" min="0" required>
                                <span class="input-group-text">ريال</span>
                            </div>
                            <small class="form-text text-muted">أدخل السعر الذي تريد تقديمه لهذه الخدمة</small>
                        </div>


                        <!-- الملاحظات -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note text-info"></i> ملاحظات إضافية
                            </label>
                            <textarea name="notes" id="notes" class="form-control" rows="5"
                                      placeholder="أضف أي تفاصيل إضافية حول عرضك...">{{ old('notes') }}</textarea>
                            <small class="form-text text-muted">يمكنك إضافة تفاصيل حول الخدمة المقدمة أو الشروط</small>
                        </div>

                        <!-- معلومات مزود الخدمة -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> معلومات مزود الخدمة</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>الاسم:</strong> {{ auth()->user()->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الهاتف:</strong> {{ auth()->user()->phone ?: 'غير محدد' }}
                                </div>
                            </div>
                            @if(auth()->user()->bio)
                                <div class="mt-2">
                                    <strong>نبذة:</strong> {{ auth()->user()->bio }}
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane"></i> تقديم العرض
                            </button>
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
