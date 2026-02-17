<!DOCTYPE html>
<html>
<head>
    <title>اختبار القسم الفرعي</title>
</head>
<body>
    <h1>اختبار حفظ القسم الفرعي</h1>

    <form action="{{ route('services.store') }}" method="POST">
        @csrf
        <input type="hidden" name="category_id" value="1">
        <input type="hidden" name="sub_category_id" value="1">
        <input type="hidden" name="city_id" value="1">
        <input type="hidden" name="user_id" value="1">
        <input type="hidden" name="notes" value="اختبار">

        <button type="submit">إنشاء خدمة تجريبية</button>
    </form>

    <hr>

    <h2>الخدمات الموجودة مع sub_category_id:</h2>
    @php
        $services = \App\Models\Service::with('subCategory')->whereNotNull('sub_category_id')->get();
    @endphp

    @foreach($services as $service)
        <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
            <h3>{{ $service->title }}</h3>
            <p>ID: {{ $service->id }}</p>
            <p>sub_category_id: {{ $service->sub_category_id }}</p>
            @if($service->subCategory)
                <p>القسم الفرعي: {{ $service->subCategory->name_ar ?? $service->subCategory->name_en }}</p>
            @else
                <p style="color: red;">القسم الفرعي غير موجود!</p>
            @endif
        </div>
    @endforeach
</body>
</html>
