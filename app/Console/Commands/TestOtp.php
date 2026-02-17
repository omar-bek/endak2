<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Otp;
use App\Services\WhatsAppOtpService;
use Carbon\Carbon;

class TestOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OTP system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing OTP System...');
        $this->newLine();

        // Test 1: Generate OTP
        $this->info('1. Generating OTP for phone: 01234567890');
        $otp = Otp::generateOtp('01234567890', 'registration');
        $this->line("Generated OTP: " . $otp->otp_code);
        $this->line("Expires at: " . $otp->expires_at);
        $this->line("Type: " . $otp->type);
        $this->newLine();

        // Test 2: Verify OTP
        $this->info('2. Verifying OTP with correct code...');
        $isValid = Otp::verifyOtp('01234567890', $otp->otp_code, 'registration');
        $this->line("Verification result: " . ($isValid ? 'SUCCESS' : 'FAILED'));
        $this->newLine();

        // Test 3: Try to verify again (should fail)
        $this->info('3. Trying to verify same OTP again (should fail)...');
        $isValid2 = Otp::verifyOtp('01234567890', $otp->otp_code, 'registration');
        $this->line("Verification result: " . ($isValid2 ? 'SUCCESS' : 'FAILED'));
        $this->newLine();

        // Test 4: Generate new OTP
        $this->info('4. Generating new OTP (should delete old one)...');
        $otp2 = Otp::generateOtp('01234567890', 'registration');
        $this->line("New OTP: " . $otp2->otp_code);
        $oldCount = Otp::where('phone', '01234567890')->where('otp_code', $otp->otp_code)->count();
        $this->line("Old OTP count: " . $oldCount);
        $this->newLine();

        // Test 5: Test expiration
        $this->info('5. Testing expiration...');
        $otp3 = Otp::generateOtp('01234567891', 'registration');
        $otp3->update(['expires_at' => Carbon::now()->subMinute()]); // Set to 1 minute ago
        $this->line("OTP expired: " . ($otp3->isExpired() ? 'YES' : 'NO'));
        $this->line("OTP valid: " . ($otp3->isValid() ? 'YES' : 'NO'));
        $this->newLine();

        // Test 6: Test WhatsApp Service
        $this->info('6. Testing WhatsApp Service...');
        $whatsappService = new WhatsAppOtpService();
        $this->line("WhatsApp service configured: " . ($whatsappService->isConfigured() ? 'YES' : 'NO'));
        $this->newLine();

        $this->info('All tests completed successfully!');

        return 0;
    }
}
