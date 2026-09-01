<?php
namespace App\Services;
use App\Models\Address;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
class AccountAddressService{
    public function getAddressList(User $user){
        $addresses = $user->addresses()->get();
        return [
            'addresses' => $addresses,
        ];
    }
    public function createAddress(User $user, array $data): Address {
        if($user->addresses()->count() >= 4){
            throw ValidationException::withMessages([
                'addresses' => 'A user cannot have more than 4 addresses'
            ]);
        }
        if (!empty($data['phone'])) {
            $data['phone'] = $this->normalizePhone($data['phone']);
        }
        if ($data['is_default'] === true) {
            $user->addresses()->update([
                'is_default' => false,
            ]);
        }
        return $user->addresses()->create($data);
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
    public function updateAddress(User $user, Address $address, array $data) {
        $address = $user->addresses()->findOrFail($address->id);
        if(!empty($data['phone'])){
            $data['phone'] = $this->normalizePhone($data['phone']);
        }
        if($data['is_default'] === true){
            $user->addresses()->update([
                'is_default' => false,
            ]);
        }
        $address->update($data);
        return $address;
    }
    public function setDefault(User $user, Address $address){
        $address = $user->addresses()->findOrFail($address->id);
        if($address->is_default === true) return false;
        $user->addresses()->update([
            'is_default' => false,
        ]);
        $address->update(['is_default' => true]);
        return true;
    }
    public function deleteAddress(User $user, Address $address){
        $address = $user->addresses()->findOrFail($address->id);
        $wasDefault = $address->is_default;
        $address->delete();
        if($wasDefault){
            $user->addresses()->first()?->update(['is_default' => true]);
        }
        return true;
    }
}
