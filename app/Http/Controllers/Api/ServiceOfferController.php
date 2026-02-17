<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use App\Models\Service;
use App\Models\ServiceOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ServiceOfferController extends BaseApiController
{
    public function index(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $offers = ServiceOffer::query()
                ->with(['service:id,title,slug,user_id', 'provider:id,name,avatar'])
                ->when($request->user()->isProvider(), fn($query) => $query->where('provider_id', $request->user()->id))
                ->when(!$request->user()->isProvider(), function ($query) use ($request) {
                    $query->whereHas('service', fn($serviceQuery) => $serviceQuery->where('user_id', $request->user()->id));
                })
                ->latest()
                ->paginate($request->get('per_page', 15));

            return $this->success($offers);
        }, 'حدث خطأ أثناء جلب العروض');
    }

    public function store(Request $request, Service $service)
    {
        return $this->executeApiWithTryCatch(function () use ($request, $service) {
            $user = $request->user();

            if (!$user->isProvider()) {
                return $this->error('فقط مزودو الخدمات يمكنهم تقديم عروض', 403);
            }

            if ($service->user_id === $user->id) {
                return $this->error('لا يمكنك تقديم عرض على خدمتك الخاصة', 422);
            }

            $data = $request->validate([
                'price' => ['required', 'numeric', 'min:1'],
                'notes' => ['nullable', 'string', 'max:2000'],
                'expires_at' => ['nullable', 'date', 'after:now'],
            ]);

            $existingOffer = ServiceOffer::where('service_id', $service->id)
                ->where('provider_id', $user->id)
                ->first();

            if ($existingOffer) {
                return $this->error('لقد قمت بتقديم عرض سابق لهذه الخدمة', 422);
            }

            $offer = ServiceOffer::create([
                'service_id' => $service->id,
                'provider_id' => $user->id,
                'price' => $data['price'],
                'notes' => $data['notes'] ?? null,
                'expires_at' => $data['expires_at'] ?? null,
                'status' => 'pending',
            ]);

            // إرسال إشعار لصاحب الخدمة
            try {
                Notification::createOfferReceivedNotification($offer);
                Log::info('API Notification sent for new offer', [
                    'offer_id' => $offer->id,
                    'service_id' => $service->id,
                    'service_owner_id' => $service->user_id
                ]);
            } catch (\Exception $e) {
                Log::error('API Failed to send notification for new offer', [
                    'offer_id' => $offer->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('API Service offer created', [
                'offer_id' => $offer->id,
                'service_id' => $service->id,
                'provider_id' => $user->id
            ]);

            return $this->success($offer->load('provider:id,name,avatar'), 'تم تقديم العرض بنجاح', 201);
        }, 'حدث خطأ أثناء تقديم العرض');
    }

    public function accept(ServiceOffer $offer, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($offer, $request) {
            $user = $request->user();
            $serviceOwnerId = $offer->service->user_id;

            if ($serviceOwnerId !== $user->id) {
                Log::warning('Unauthorized accept attempt', [
                    'offer_id' => $offer->id,
                    'service_owner_id' => $serviceOwnerId,
                    'user_id' => $user->id,
                ]);
                return $this->error('لا يمكنك قبول هذا العرض. هذا العرض ليس لخدمتك', 403);
            }

            $offer->markAsAccepted();

            // إرسال إشعار للمزود
            try {
                Notification::createOfferAcceptedNotification($offer);
                Log::info('API Notification sent for accepted offer', [
                    'offer_id' => $offer->id,
                    'provider_id' => $offer->provider_id
                ]);
            } catch (\Exception $e) {
                Log::error('API Failed to send notification for accepted offer', [
                    'offer_id' => $offer->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('API Service offer accepted', [
                'offer_id' => $offer->id,
                'service_id' => $offer->service_id
            ]);

            return $this->success($offer->fresh(), 'تم قبول العرض بنجاح');
        }, 'حدث خطأ أثناء قبول العرض');
    }

    public function reject(ServiceOffer $offer, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($offer, $request) {
            $user = $request->user();
            $serviceOwnerId = $offer->service->user_id;

            if ($serviceOwnerId !== $user->id) {
                Log::warning('Unauthorized reject attempt', [
                    'offer_id' => $offer->id,
                    'service_owner_id' => $serviceOwnerId,
                    'user_id' => $user->id,
                ]);
                return $this->error('لا يمكنك رفض هذا العرض. هذا العرض ليس لخدمتك', 403);
            }

            $offer->update([
                'status' => 'rejected',
            ]);

            // إرسال إشعار للمزود
            try {
                Notification::createOfferRejectedNotification($offer);
                Log::info('API Notification sent for rejected offer', [
                    'offer_id' => $offer->id,
                    'provider_id' => $offer->provider_id
                ]);
            } catch (\Exception $e) {
                Log::error('API Failed to send notification for rejected offer', [
                    'offer_id' => $offer->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('API Service offer rejected', [
                'offer_id' => $offer->id,
                'service_id' => $offer->service_id
            ]);

            return $this->success($offer->fresh(), 'تم رفض العرض');
        }, 'حدث خطأ أثناء رفض العرض');
    }

    public function deliver(ServiceOffer $offer, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($offer, $request) {
            $user = $request->user();
            $serviceOwnerId = $offer->service->user_id;

            // Log للتشخيص
            Log::info('API Deliver offer attempt', [
                'offer_id' => $offer->id,
                'service_owner_id' => $serviceOwnerId,
                'offer_provider_id' => $offer->provider_id,
                'user_id' => $user->id,
            ]);

            // التحقق من أن المستخدم هو صاحب الخدمة (service owner)
            if ($serviceOwnerId !== $user->id) {
                Log::warning('Unauthorized deliver attempt', [
                    'offer_id' => $offer->id,
                    'service_owner_id' => $serviceOwnerId,
                    'user_id' => $user->id,
                ]);
                return $this->error('لا يمكنك تسليم هذا العرض. هذا العرض ليس لخدمتك', 403);
            }

            // التحقق من إمكانية التسليم
            if (!$offer->canBeDelivered()) {
                return $this->error('لا يمكن تسليم هذا العرض حالياً. يجب أن يكون العرض مقبولاً أولاً', 422);
            }

            $offer->markAsDelivered();

            Log::info('API Service offer delivered', [
                'offer_id' => $offer->id,
                'service_id' => $offer->service_id,
                'service_owner_id' => $user->id,
                'provider_id' => $offer->provider_id
            ]);

            return $this->success($offer->fresh(), 'تم تحديد العرض كمُسلم');
        }, 'حدث خطأ أثناء تسليم العرض');
    }

    public function review(ServiceOffer $offer, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($offer, $request) {
            if ($offer->service->user_id !== $request->user()->id) {
                return $this->error('لا يمكنك تقييم هذا العرض', 403);
            }

            $data = $request->validate([
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'review' => ['nullable', 'string', 'max:2000'],
            ]);

            if (!$offer->canBeRated()) {
                return $this->error('لا يمكن تقييم هذا العرض حالياً', 422);
            }

            $offer->addReview($data['rating'], $data['review'] ?? null);

            Log::info('API Service offer reviewed', [
                'offer_id' => $offer->id,
                'rating' => $data['rating']
            ]);

            return $this->success($offer->fresh(), 'تم إضافة التقييم بنجاح');
        }, 'حدث خطأ أثناء إضافة التقييم');
    }
}
