<?php
namespace App\Services;
use App\Mail\loginOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
class AuthService{
    public function createOtp(string $email){
        $otp = rand(100000, 999999);
        Redis::setex("otp:$email",180,Hash::make($otp));
        return $otp;
    }
    public function sendOtp(string $email): bool{
        $otp = $this->createOtp($email);
        try{
            Mail::to($email)->queue(new loginOtpMail($otp));
            return true;
        }
        catch(\Exception $e){
            Redis::del("otp:$email");
            return false;
        }
    }
    public function verifyOtp(string $email,string $otp): bool {
        $hashedOtp = Redis::get("otp:$email");
        if(!$hashedOtp) return false;
        if(!Hash::check($otp,$hashedOtp)) return false;
        Redis::del("otp:$email");
        return true;
    }
    public function loginWithOtp(string $email, string $otp){
        if(!$this->verifyOtp($email,$otp)) return false;
        return User::firstOrCreate(['email' => $email]);
    }
    public function verifyPassword(string $email, string $password){
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }
}
