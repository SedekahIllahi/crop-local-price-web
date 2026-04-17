<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleEnum;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setUserRole(RoleEnum $role): void
    {
        $this->assignRole($role->value);
    }

    /* Users Information */
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function updateProfilePhoto($photo)
    {
        $path = $photo->store('profile-photos', 'public');
        // Cek apakah user memiliki informasi profile di tabel users_information
        if ($this->userProfile) {
            // Jika user memiliki informasi profile, hapus file lama
            Storage::disk('public')->delete($this->userProfile->profile_photo);

            // Update profile photo
            $this->userProfile->update([
                'profile_photo' => $path,
            ]);
        } else {
            // Jika user tidak memiliki informasi profile, buat record baru
            $this->userProfile()->create([
                'profile_photo' => $path,
            ]);
        }
    }
}
