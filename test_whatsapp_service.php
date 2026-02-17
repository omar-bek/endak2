<?php

// Test WhatsApp Service
require_once 'vendor/autoload.php';

use App\Services\WhatsAppOtpService;

echo "Testing WhatsApp Service Integration...\n\n";

try {
    $service = new WhatsAppOtpService();

    echo "WhatsApp Service Configured: " . ($service->isConfigured() ? 'YES' : 'NO') . "\n";

    if (!$service->isConfigured()) {
        echo "❌ WhatsApp service is not configured properly!\n";
        echo "Please check your .env file for Twilio settings.\n";
        exit(1);
    }

    echo "Testing OTP generation and sending...\n";

    // Test with a real phone number
    $testPhone = "01234567890"; // Replace with your test number
    $otp = $service->generateAndSendOtp($testPhone, 'registration');

    if ($otp) {
        echo "✅ SUCCESS!\n";
        echo "OTP Generated: " . $otp->otp_code . "\n";
        echo "OTP Expires: " . $otp->expires_at . "\n";
        echo "Phone: " . $otp->phone . "\n";
        echo "Type: " . $otp->type . "\n";
    } else {
        echo "❌ FAILED to generate or send OTP\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
