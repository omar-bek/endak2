<?php

namespace App\Services;

use App\Models\Otp;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppOtpService
{
    private $twilio;
    private $fromNumber;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $this->fromNumber = config('services.twilio.whatsapp_from');
    }

    /**
     * Send OTP via WhatsApp using Twilio
     */
    public function sendOtp($phone, $otpCode, $type = 'registration')
    {
        try {
            // Format phone number for WhatsApp
            $formattedPhone = $this->formatPhoneNumber($phone);

            // Create message based on type
            $message = $this->createOtpMessage($otpCode, $type);

            // Send WhatsApp message via Twilio
            $messageResponse = $this->twilio->messages
                ->create(
                    "whatsapp:{$formattedPhone}", // to
                    array(
                        "from" => $this->fromNumber,
                        "body" => $message
                    )
                );

            Log::info('WhatsApp OTP sent successfully via Twilio', [
                'phone' => $formattedPhone,
                'type' => $type,
                'message_sid' => $messageResponse->sid
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp OTP service error via Twilio', [
                'phone' => $phone,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Generate and send OTP
     */
    public function generateAndSendOtp($phone, $type = 'registration')
    {
        // Generate OTP
        $otp = Otp::generateOtp($phone, $type);

        // Send via WhatsApp
        $sent = $this->sendOtp($phone, $otp->otp_code, $type);

        if ($sent) {
            return $otp;
        }

        // If sending failed, delete the OTP
        $otp->delete();
        return false;
    }

    /**
     * Verify OTP
     */
    public function verifyOtp($phone, $otpCode, $type = 'registration')
    {
        return Otp::verifyOtp($phone, $otpCode, $type);
    }

    /**
     * Format phone number for WhatsApp via Twilio
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Detect country and add appropriate country code
        if (str_starts_with($phone, '966')) {
            // Saudi Arabia - already has country code
            return '+' . $phone;
        } elseif (str_starts_with($phone, '20')) {
            // Egypt - already has country code
            return '+' . $phone;
        } elseif (str_starts_with($phone, '0')) {
            // Remove leading zero and detect country
            $phone = ltrim($phone, '0');

            // Check if it's Saudi number (starts with 5)
            if (str_starts_with($phone, '5') && strlen($phone) == 9) {
                return '+966' . $phone;
            }
            // Check if it's Egyptian number (starts with 1)
            elseif (str_starts_with($phone, '1') && strlen($phone) == 10) {
                return '+20' . $phone;
            }
            // Default to Egypt for other cases
            else {
                return '+20' . $phone;
            }
        } else {
            // No leading zero - try to detect country by length
            if (strlen($phone) == 9 && str_starts_with($phone, '5')) {
                // Saudi number without country code
                return '+966' . $phone;
            } elseif (strlen($phone) == 10 && str_starts_with($phone, '1')) {
                // Egyptian number without country code
                return '+20' . $phone;
            } else {
                // Default to Egypt
                return '+20' . $phone;
            }
        }
    }

    /**
     * Create OTP message based on type
     */
    private function createOtpMessage($otpCode, $type)
    {
        $messages = [
            'registration' => "مرحباً بك في إنداك! 🎉\n\nرمز التحقق الخاص بك هو: *{$otpCode}*\n\nهذا الرمز صالح لمدة 5 دقائق.\n\nلا تشارك هذا الرمز مع أي شخص آخر.\n\n---\n\nWelcome to Endak! 🎉\n\nYour verification code is: *{$otpCode}*\n\nThis code is valid for 5 minutes.\n\nDo not share this code with anyone.",
            'login' => "مرحباً! 👋\n\nرمز تسجيل الدخول الخاص بك هو: *{$otpCode}*\n\nهذا الرمز صالح لمدة 5 دقائق.\n\nلا تشارك هذا الرمز مع أي شخص آخر.\n\n---\n\nHello! 👋\n\nYour login code is: *{$otpCode}*\n\nThis code is valid for 5 minutes.\n\nDo not share this code with anyone.",
            'password_reset' => "إعادة تعيين كلمة المرور 🔐\n\nرمز التحقق الخاص بك هو: *{$otpCode}*\n\nهذا الرمز صالح لمدة 5 دقائق.\n\nلا تشارك هذا الرمز مع أي شخص آخر.\n\n---\n\nPassword Reset 🔐\n\nYour verification code is: *{$otpCode}*\n\nThis code is valid for 5 minutes.\n\nDo not share this code with anyone."
        ];

        return $messages[$type] ?? $messages['registration'];
    }

    /**
     * Check if WhatsApp service is configured
     */
    public function isConfigured()
    {
        return !empty(config('services.twilio.sid')) &&
            !empty(config('services.twilio.token')) &&
            !empty(config('services.twilio.whatsapp_from'));
    }

    /**
     * Get remaining attempts for phone number
     */
    public function getRemainingAttempts($phone, $type = 'registration')
    {
        $attempts = Otp::where('phone', $phone)
            ->where('type', $type)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return max(0, 5 - $attempts); // Max 5 attempts per hour
    }
}
