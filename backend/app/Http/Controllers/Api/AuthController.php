<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function otpRequest(Request $request,AuthService $authService){
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
        $result = $authService->sendOtp($validated['email']);
        if(!$result){
            return response()->json([
                'success' => false,
                'message' => 'Could not process OTP request'
            ],500);
        }
        return response()->json([
            'success' => true,
            'message' => 'OTP has been sent',
        ]);
    }
    public function verifyOtp(Request $request,AuthService $authService){
        $otp = $request->otp;
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
        $user = $authService->loginWithOtp($validated['email'],$otp);
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ],422);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully',
            'token' => $token,
        ]);
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out'
        ]);
    }
    public function verifyPassword(Request $request,AuthService $authService){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:255',
        ]);
        $result = $authService->verifyPassword($validated['email'],$validated['password']);
        if(!$result){
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ],401);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
        ]);
    }
}
