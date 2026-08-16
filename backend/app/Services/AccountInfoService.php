<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
class AccountInfoService{
    public function updateUserInfo(User $user,array $data): User{
        if (!empty($data['phone'])) {
            $data['phone'] = $this->normalizePhone(
                $data['phone']
            );
        }
        $user->update($data);
        return $user->fresh();
    }
    private function normalizePhone(string $phone): string{
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($phone);
            if (! $phoneUtil->isValidNumber($phoneNumber)) {
                throw ValidationException::withMessages([
                    'phone' => 'The phone number is invalid.',
                ]);
            }
            return $phoneUtil->format(
                $phoneNumber,
                PhoneNumberFormat::E164
            );
        }
        catch (NumberParseException) {
            throw ValidationException::withMessages([
                'phone' => 'The phone number is invalid.',
            ]);
        }
    }
    public function updatePassword(User $user,array $data) {
        if($user->password && !Hash::check($data['current_password'], $user->password)){
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }
        $user->update([
            'password' => Hash::make($data['password'])
        ]);
    }
    public function updateAvatar(User $user,UploadedFile $file) {
        $path = Image::fromUpload($file)
            ->orient()
            ->cover(500,500)
            ->toWebp()
            ->quality(100)
            ->storePublicly('avatars', 'public');
        if($user->avatar){
            Storage::disk('public')->delete($user->avatar);
        }
        $user->update([
            'avatar' => $path
        ]);
        return $user->fresh();
    }
    public function destroyAvatar(User $user) {
        if($user->avatar){
            Storage::disk('public')->delete($user->avatar);
        }
        $user->update([
            'avatar' => null
        ]);
        return $user->fresh();
    }
}
