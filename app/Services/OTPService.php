<?php

namespace App\Services;

use App\Mail\SendOtpMail;
use App\Models\OTPVerification;
use App\Models\User;
use App\Services\AppConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class OTPService
{
    public function generate(
            string $type= AppConfig::VERIFY_EMAIL,
            int $expired_at=AppConfig::TOKEN_EXPIRE_TIME,
            User $user,
        ) {
        $otp = new OTPVerification();
        $otp->type = $type;
        $otp->code = rand(111111, 999999);
        $otp->user_id = $user->id;
        $otp->expired_at = now()->addMinute($expired_at);
        $otp->save();

        if($type == AppConfig::VERIFY_EMAIL){
            Mail::send(new SendOtpMail($user, $otp));
        }

        return $otp;
    }

    public function verify(
            User $user,
            int $code, string $type, 
        ) : bool{
            $otp = OTPVerification::where('code', $code)
            ->where('type', $type)
            ->where('status', true)
            ->where('user_id', $user->id)
            ->first();
    
        if (!$otp) {
            return false;
        }
    
        $expired_at = $otp->expired_at;
        if (!$expired_at) {
            return false;
        }
    
        $expired_at_date = Carbon::parse($expired_at);
        $current_date = Carbon::now();
    
        if ($current_date->diffInMinutes($expired_at_date, false) > AppConfig::TOKEN_EXPIRE_TIME) {
            return false;
        }
        return true;
    }

    public function getUserFromCode(int $code): User|bool {
        $otp = OTPVerification::
            where('code', $code)
            ->first();
        if(!$otp) return false;
        $user = User::where('id', $otp->user_id)->first();
        if(!$user) return false;

        return $user;
    }
}
