<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens,HasFactory,Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $table = 'users';
    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany{
        return $this->hasMany(Order::class,'user_id');
    }
    public function notifications() {
        return Notification::where(function ($query) {
            $query->whereNull('user_id')
                ->orWhere('user_id', $this->id);
        })->latest();
    }
    public function wishlist(){
        return $this->hasOne(Wishlist::class, 'user_id');
    }
    public function addresses(){
        return $this->hasMany(Address::class, 'user_id');
    }
    public function cart(): HasOne{
        return $this->hasOne(Cart::class, 'user_id');
    }
}
