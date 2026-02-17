<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Otp extends Model
{
    protected $fillable = [
        'phone',
        'otp_code',
        'type',
        'is_verified',
        'expires_at',
        'verified_at'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Generate a new OTP for the given phone number
     */
    public static function generateOtp($phone, $type = 'registration')
    {
        // Delete any existing OTPs for this phone and type
        self::where('phone', $phone)
            ->where('type', $type)
            ->delete();

        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create new OTP record
        return self::create([
            'phone' => $phone,
            'otp_code' => $otpCode,
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(5), // 5 minutes expiry
        ]);
    }

    /**
     * Verify OTP code
     */
    public static function verifyOtp($phone, $otpCode, $type = 'registration')
    {
        $otp = self::where('phone', $phone)
            ->where('otp_code', $otpCode)
            ->where('type', $type)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otp) {
            $otp->update([
                'is_verified' => true,
                'verified_at' => Carbon::now()
            ]);
            return true;
        }

        return false;
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if OTP is valid (not expired and not verified)
     */
    public function isValid()
    {
        return !$this->isExpired() && !$this->is_verified;
    }
}
