<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceOffer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ServiceOfferController extends Controller
{
    /**
     * عرض نموذج تقديم عرض
     */
    public function create(Service $service)
    {
        try {
            // التأكد من أن المستخدم مزود خدمة
            if (!Auth::check() || !Auth::user()->isProvider()) {
                return redirect()->route('login')->with('error', 'يجب أن تكون مزود خدمة لتقديم عرض');
            }

            $user = Auth::user();

            // التحقق من أن مزود الخدمة لديه ملف شخصي مكتمل
            if (!$user->hasCompleteProviderProfile()) {
                return redirect()->route('provider.complete-profile')
                    ->with('error', 'يجب إكمال الملف الشخصي أولاً');
            }

            // التحقق من أن مزود الخدمة يمكنه تقديم عرض لهذه الخدمة
            if (!$this->canProviderOfferService($user, $service)) {
                return redirect()->route('services.show', $service->slug)
                    ->with('error', 'لا يمكنك تقديم عرض لهذه الخدمة. تأكد من أن القسم والمدن متطابقة مع اختياراتك في الملف الشخصي');
            }

            // التحقق من عدم تقديم عرض سابق
            $existingOffer = ServiceOffer::where('service_id', $service->id)
                ->where('provider_id', Auth::id())
                ->first();

            if ($existingOffer) {
                return redirect()->route('services.show', $service->slug)
                    ->with('error', 'لقد قدمت عرضاً لهذه الخدمة مسبقاً');
            }

            return view('service-offers.create', compact('service'));
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@create: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null
            ]);
            return redirect()->route('services.index')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * حفظ العرض
     */
    public function store(Request $request, Service $service)
    {
        try {
            // التأكد من أن المستخدم مزود خدمة
            if (!Auth::check() || !Auth::user()->isProvider()) {
                return redirect()->route('login')->with('error', 'يجب أن تكون مزود خدمة لتقديم عرض');
            }

            $user = Auth::user();

            // التحقق من أن مزود الخدمة لديه ملف شخصي مكتمل
            if (!$user->hasCompleteProviderProfile()) {
                return redirect()->route('provider.complete-profile')
                    ->with('error', 'يجب إكمال الملف الشخصي أولاً');
            }

            // التحقق من أن مزود الخدمة يمكنه تقديم عرض لهذه الخدمة
            if (!$this->canProviderOfferService($user, $service)) {
                return redirect()->route('services.show', $service->slug)
                    ->with('error', 'لا يمكنك تقديم عرض لهذه الخدمة. تأكد من أن القسم والمدن متطابقة مع اختياراتك في الملف الشخصي');
            }

            $validated = $request->validate([
                'price' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:1000',
            ]);

            // التحقق من عدم تقديم عرض سابق
            $existingOffer = ServiceOffer::where('service_id', $service->id)
                ->where('provider_id', Auth::id())
                ->first();

            if ($existingOffer) {
                return redirect()->route('services.show', $service->slug)
                    ->with('error', 'لقد قدمت عرضاً لهذه الخدمة مسبقاً');
            }

            // إنشاء العرض
            $offer = ServiceOffer::create([
                'service_id' => $service->id,
                'provider_id' => Auth::id(),
                'price' => $validated['price'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending'
            ]);

            // إرسال إشعار لصاحب الخدمة
            Notification::createOfferReceivedNotification($offer);

            Log::info('Service offer created', [
                'offer_id' => $offer->id,
                'service_id' => $service->id,
                'provider_id' => Auth::id()
            ]);

            return redirect()->route('services.show', $service->slug)
                ->with('success', 'تم تقديم عرضك بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null,
                'provider_id' => Auth::id()
            ]);
            return back()->with('error', 'حدث خطأ أثناء تقديم العرض')->withInput();
        }
    }

    /**
     * عرض العروض المقدمة لخدمة معينة
     */
    public function index(Service $service)
    {
        try {
            // التأكد من أن المستخدم صاحب الخدمة أو مدير
            if (!Auth::check() || (Auth::id() !== $service->user_id && !Auth::user()->isAdmin())) {
                return redirect()->route('services.show', $service->slug)
                    ->with('error', 'غير مصرح لك بعرض العروض');
            }

            $offers = $service->offers()->with('provider')->latest()->get();

            return view('service-offers.index', compact('service', 'offers'));
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null
            ]);
            return redirect()->route('services.index')->with('error', 'حدث خطأ أثناء تحميل العروض');
        }
    }

    /**
     * قبول عرض
     */
    public function accept(ServiceOffer $offer)
    {
        try {
            // التأكد من أن المستخدم صاحب الخدمة
            if (!Auth::check() || Auth::id() !== $offer->service->user_id) {
                return redirect()->back()->with('error', 'غير مصرح لك بقبول هذا العرض');
            }

            $offer->markAsAccepted();

            // رفض باقي العروض
            ServiceOffer::where('service_id', $offer->service_id)
                ->where('id', '!=', $offer->id)
                ->update(['status' => 'rejected']);

            // إرسال إشعار لمزود الخدمة بقبول العرض
            Notification::createOfferAcceptedNotification($offer);

            Log::info('Service offer accepted', [
                'offer_id' => $offer->id,
                'service_id' => $offer->service_id
            ]);

            return redirect()->back()->with('success', 'تم قبول العرض بنجاح');
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@accept: ' . $e->getMessage(), [
                'exception' => $e,
                'offer_id' => $offer->id ?? null
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء قبول العرض');
        }
    }

    /**
     * رفض عرض
     */
    public function reject(ServiceOffer $offer)
    {
        try {
            // التأكد من أن المستخدم صاحب الخدمة
            if (!Auth::check() || Auth::id() !== $offer->service->user_id) {
                return redirect()->back()->with('error', 'غير مصرح لك برفض هذا العرض');
            }

            $offer->update(['status' => 'rejected']);

            // إرسال إشعار لمزود الخدمة برفض العرض
            Notification::createOfferRejectedNotification($offer);

            Log::info('Service offer rejected', [
                'offer_id' => $offer->id,
                'service_id' => $offer->service_id
            ]);

            return redirect()->back()->with('success', 'تم رفض العرض');
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@reject: ' . $e->getMessage(), [
                'exception' => $e,
                'offer_id' => $offer->id ?? null
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفض العرض');
        }
    }

    /**
     * عرض عروض مزود الخدمة
     */
    public function myOffers()
    {
        try {
            if (!Auth::check() || !Auth::user()->isProvider()) {
                return redirect()->route('login')->with('error', 'يجب أن تكون مزود خدمة');
            }

            $offers = Auth::user()->offers()->with(['service', 'service.category'])->latest()->paginate(10);

            return view('service-offers.my-offers', compact('offers'));
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@myOffers: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل العروض');
        }
    }

    /**
     * عرض الخدمات المكتملة لمزود الخدمة
     */
    public function completedServices()
    {
        try {
            if (!Auth::check() || !Auth::user()->isProvider()) {
                return redirect()->route('login')->with('error', 'يجب أن تكون مزود خدمة');
            }

            $offers = Auth::user()->offers()
                ->where('status', 'delivered')
                ->with(['service', 'service.category', 'service.user'])
                ->latest()
                ->paginate(10);

            return view('service-offers.completed-services', compact('offers'));
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@completedServices: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الخدمات المكتملة');
        }
    }

    /**
     * عرض تفاصيل عرض معين
     */
    public function show(ServiceOffer $offer)
    {
        try {
            // التأكد من أن المستخدم مسجل دخول
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
            }

            // تحميل العلاقات المطلوبة (بما في ذلك السجلات المحذوفة)
            $offer->load(['provider.providerProfile']);

            // تحميل service مع السجلات المحذوفة
            if (!$offer->relationLoaded('service')) {
                $offer->load(['service' => function ($query) {
                    $query->withTrashed();
                }]);
            }

            // إذا كانت الخدمة محذوفة، إرجاع خطأ
            if (!$offer->service) {
                return redirect()->route('services.index')->with('error', 'الخدمة المرتبطة بهذا العرض غير موجودة أو تم حذفها');
            }

            // تحميل باقي العلاقات
            $offer->load(['service.user', 'service.category']);

            // التحقق من أن المستخدم إما مزود الخدمة (صاحب العرض) أو صاحب الخدمة
            if (Auth::id() !== $offer->provider_id && Auth::id() !== $offer->service->user_id) {
                return redirect()->route('services.index')->with('error', 'غير مصرح لك بعرض هذا العرض');
            }

            // الحصول على مزود الخدمة
            $provider = $offer->provider;

            // الحصول على ملف مزود الخدمة
            $providerProfile = $provider ? $provider->providerProfile : null;

            // الحصول على العميل (صاحب الخدمة)
            $customer = $offer->service->user;

            // الحصول على تقييمات المزود من عروض أخرى مكتملة (بما في ذلك السجلات المحذوفة للخدمات)
            $ratings = ServiceOffer::where('provider_id', $offer->provider_id)
                ->where('status', 'delivered')
                ->whereNotNull('rating')
                ->where('id', '!=', $offer->id)
                ->with(['service' => function ($query) {
                    $query->withTrashed();
                }, 'service.user', 'service.category'])
                ->latest()
                ->take(6)
                ->get()
                ->filter(function ($rating) {
                    return $rating->service !== null;
                });

            return view('service-offers.show', compact('offer', 'provider', 'providerProfile', 'customer', 'ratings'));
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@show: ' . $e->getMessage(), [
                'exception' => $e,
                'offer_id' => $offer->id ?? null
            ]);
            return redirect()->route('services.index')->with('error', 'حدث خطأ أثناء تحميل العرض');
        }
    }

    /**
     * عرض نموذج تعديل العرض للمزود
     */
    public function edit(ServiceOffer $offer)
    {
        try {
            // التأكد من أن المستخدم مزود الخدمة وصاحب العرض
            if (!Auth::check() || Auth::id() !== $offer->provider_id) {
                return redirect()->route('services.index')->with('error', 'غير مصرح لك بتعديل هذا العرض');
            }

            // التحقق من أن العرض لم يتم قبوله أو تسليمه
            if (in_array($offer->status, ['accepted', 'delivered'])) {
                return redirect()->route('service-offers.my-offers')
                    ->with('error', 'لا يمكن تعديل العرض بعد قبوله أو تسليمه');
            }

            return view('service-offers.edit', compact('offer'));
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@edit: ' . $e->getMessage(), [
                'exception' => $e,
                'offer_id' => $offer->id ?? null
            ]);
            return redirect()->route('service-offers.my-offers')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * تحديث العرض للمزود
     */
    public function update(Request $request, ServiceOffer $offer)
    {
        try {
            // التأكد من أن المستخدم مزود الخدمة وصاحب العرض
            if (!Auth::check() || Auth::id() !== $offer->provider_id) {
                return redirect()->route('services.index')->with('error', 'غير مصرح لك بتعديل هذا العرض');
            }

            // التحقق من أن العرض لم يتم قبوله أو تسليمه
            if (in_array($offer->status, ['accepted', 'delivered'])) {
                return redirect()->route('service-offers.my-offers')
                    ->with('error', 'لا يمكن تعديل العرض بعد قبوله أو تسليمه');
            }

            $validated = $request->validate([
                'price' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:1000',
            ]);

            $offer->update([
                'price' => $validated['price'],
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Service offer updated', [
                'offer_id' => $offer->id,
                'provider_id' => Auth::id()
            ]);

            return redirect()->route('service-offers.my-offers')
                ->with('success', 'تم تحديث العرض بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@update: ' . $e->getMessage(), [
                'exception' => $e,
                'offer_id' => $offer->id ?? null
            ]);
            return back()->with('error', 'حدث خطأ أثناء تحديث العرض')->withInput();
        }
    }

    /**
     * التحقق من إمكانية مزود الخدمة تقديم عرض لخدمة معينة
     */
    private function canProviderOfferService($provider, $service)
    {
        // التحقق من أن مزود الخدمة لديه ملف شخصي
        $profile = $provider->providerProfile;
        if (!$profile) {
            return false;
        }

        // التحقق من أن القسم متطابق مع اختيارات مزود الخدمة
        $providerCategoryIds = $profile->activeCategories()->pluck('category_id')->toArray();
        if (!in_array($service->category_id, $providerCategoryIds)) {
            return false;
        }

        // التحقق من أن المدن متطابقة مع اختيارات مزود الخدمة
        $providerCityIds = $profile->activeCities()->pluck('city_id')->toArray();

        // إذا كانت الخدمة لها مدينة محددة
        if ($service->city_id) {
            if (!in_array($service->city_id, $providerCityIds)) {
                return false;
            }
        } else {
            // إذا كانت الخدمة متاحة في جميع المدن، يجب أن يكون مزود الخدمة متاح في نفس المدن
            // أو يمكن تعديل هذا المنطق حسب احتياجاتك
            return true;
        }

        return true;
    }

    /**
     * تسليم الخدمة
     */
    public function markAsDelivered(ServiceOffer $offer)
    {
        try {
            // التأكد من أن المستخدم هو صاحب الخدمة
            if (Auth::id() !== $offer->service->user_id) {
                return back()->with('error', 'غير مصرح لك بتسليم هذه الخدمة');
            }

            // التأكد من أن العرض مقبول
            if ($offer->status !== 'accepted') {
                return back()->with('error', 'لا يمكن تسليم الخدمة إلا بعد قبول العرض');
            }

            $offer->markAsDelivered();

            // إرسال إشعار لمزود الخدمة
            Notification::create([
                'user_id' => $offer->provider_id,
                'title' => 'تم تسليم الخدمة',
                'message' => 'تم تسليم الخدمة: ' . $offer->service->title,
                'type' => 'service_delivered',
                'data' => json_encode(['offer_id' => $offer->id, 'service_id' => $offer->service_id])
            ]);

            Log::info('Service marked as delivered', [
                'offer_id' => $offer->id,
                'service_id' => $offer->service_id
            ]);

            return back()->with('success', 'تم تسليم الخدمة بنجاح');
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@markAsDelivered: ' . $e->getMessage(), [
                'exception' => $e,
                'offer_id' => $offer->id ?? null
            ]);
            return back()->with('error', 'حدث خطأ أثناء تسليم الخدمة');
        }
    }

    /**
     * تقييم مزود الخدمة
     */
    public function review(Request $request, ServiceOffer $offer)
    {
        try {
            // التأكد من أن المستخدم هو صاحب الخدمة
            if (Auth::id() !== $offer->service->user_id) {
                return back()->with('error', 'غير مصرح لك بتقييم هذا المزود');
            }

            // التأكد من أن الخدمة تم تسليمها
            if ($offer->status !== 'delivered') {
                return back()->with('error', 'لا يمكن التقييم إلا بعد تسليم الخدمة');
            }

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000',
            ]);

            $offer->addReview($validated['rating'], $validated['review'] ?? null);

            // إرسال إشعار لمزود الخدمة
            Notification::create([
                'user_id' => $offer->provider_id,
                'title' => 'تم تقييمك',
                'message' => 'تم تقييمك على الخدمة: ' . $offer->service->title,
                'type' => 'provider_reviewed',
                'data' => json_encode(['offer_id' => $offer->id, 'service_id' => $offer->service_id])
            ]);

            Log::info('Service offer reviewed', [
                'offer_id' => $offer->id,
                'rating' => $validated['rating']
            ]);

            return back()->with('success', 'تم إضافة التقييم بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in ServiceOfferController@review: ' . $e->getMessage(), [
                'exception' => $e,
                'offer_id' => $offer->id ?? null
            ]);
            return back()->with('error', 'حدث خطأ أثناء إضافة التقييم');
        }
    }
}
