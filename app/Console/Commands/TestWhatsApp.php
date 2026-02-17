<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppOtpService;

class TestWhatsApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test {phone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp OTP sending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');

        $this->info('Testing WhatsApp OTP Service...');
        $this->newLine();

        try {
            $service = new WhatsAppOtpService();

            $this->info('WhatsApp Service Configured: ' . ($service->isConfigured() ? 'YES' : 'NO'));

            if (!$service->isConfigured()) {
                $this->error('WhatsApp service is not configured properly!');
                $this->line('Please check your .env file for Twilio settings.');
                return 1;
            }

            $this->info('Generating and sending OTP to: ' . $phone);

            $otp = $service->generateAndSendOtp($phone, 'registration');

            if ($otp) {
                $this->info('✅ SUCCESS! OTP sent successfully!');
                $this->line('OTP Code: ' . $otp->otp_code);
                $this->line('Expires: ' . $otp->expires_at);
                $this->line('Phone: ' . $otp->phone);
                $this->line('Type: ' . $otp->type);
            } else {
                $this->error('❌ FAILED to send OTP');
            }
        } catch (\Exception $e) {
            $this->error('❌ ERROR: ' . $e->getMessage());
            $this->line('File: ' . $e->getFile() . ':' . $e->getLine());
        }

        return 0;
    }
}
