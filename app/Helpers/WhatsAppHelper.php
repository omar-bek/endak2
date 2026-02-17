<?php

namespace App\Helpers;

class WhatsAppHelper
{
    /**
     * الحصول على رابط الواتساب
     */
    public static function getWhatsAppUrl($customMessage = null)
    {
        if (!\App\Models\SystemSetting::get('whatsapp_enabled', true)) {
            return null;
        }

        $number = \App\Models\SystemSetting::get('whatsapp_number', '+966501234567');
        $message = $customMessage ?? \App\Models\SystemSetting::get('whatsapp_message', 'مرحباً، أريد الاستفسار عن خدمة');

        // تنظيف الرقم من الرموز غير الرقمية
        $cleanNumber = preg_replace('/[^0-9]/', '', $number);

        // ترميز الرسالة
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$cleanNumber}?text={$encodedMessage}";
    }

    /**
     * الحصول على رقم الواتساب
     */
    public static function getWhatsAppNumber()
    {
        return \App\Models\SystemSetting::get('whatsapp_number', '+966501234567');
    }

    /**
     * التحقق من تفعيل الواتساب
     */
    public static function isEnabled()
    {
        return \App\Models\SystemSetting::get('whatsapp_enabled', true);
    }

    /**
     * الحصول على الرابط كزر HTML
     */
    public static function getWhatsAppButton($text = 'تواصل معنا عبر الواتساب', $customMessage = null, $class = 'btn btn-success')
    {
        $url = self::getWhatsAppUrl($customMessage);

        if (!$url) {
            return '';
        }

        return sprintf(
            '<a href="%s" target="_blank" class="%s" rel="noopener noreferrer">
                <i class="fab fa-whatsapp me-1"></i>%s
            </a>',
            $url,
            $class,
            $text
        );
    }

    /**
     * الحصول على الرابط كرابط نصي
     */
    public static function getWhatsAppLink($text = 'تواصل معنا عبر الواتساب', $customMessage = null)
    {
        $url = self::getWhatsAppUrl($customMessage);

        if (!$url) {
            return $text;
        }

        return sprintf(
            '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
            $url,
            $text
        );
    }
}
