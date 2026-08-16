<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\AccountInfoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class AccountInfoController extends Controller
{
    public function show(Request $request){
        return response()->json([
            'success' => true,
            'message' => 'Get account info successfully',
            'data' => new UserResource($request->user())
        ]);
    }
    public function updateAvatar(Request $request,AccountInfoService $accountInfoService){
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);
        $user = $accountInfoService->updateAvatar($request->user(),$validated['avatar']);
        return response()->json([
            'success' => true,
            'message' => 'Avatar updated successfully',
            'data' => new UserResource($user)
        ]);
    }
    public function destroyAvatar(Request $request,AccountInfoService $accountInfoService){
        $user = $accountInfoService->destroyAvatar($request->user());
        return response()->json([
            'success' => true,
            'message' => 'Avatar deleted successfully',
            'data' => new UserResource($user)
        ]);
    }
    public function update(Request $request,AccountInfoService $accountInfoService){
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($request->user()->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'date_of_birth' => [
                'nullable',
                'date',
            ],
            'gender' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                    'non_binary',
                    'prefer_not_to_say',
                ]),
            ],
            'bio' => [
                'nullable',
                'string',
                'min:5',
                'max:500',
            ],
        ]);
        $user = $accountInfoService->updateUserInfo(
            $request->user(),
            $validated
        );
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user),
        ]);
    }
    public function updatePassword(Request $request,AccountInfoService $accountInfoService){
        $validated = $request->validate([
            'current_password' => 'nullable',
            'password' => 'required|string|min:8|max:255|confirmed',
        ]);
        $accountInfoService->updatePassword($request->user(), $validated);
        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
}
