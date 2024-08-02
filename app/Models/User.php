<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use Sortable;
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',      
        'status',
        'user_type',
        'time_zone',
        'country_code',
        'mobile_number',
        'last_login_datetime',
        'last_login_ip',
        'profile_photo_path',
        'password',
    
    ];

    public $sortable = [
        'user_id',
        'user_name',
        'first_name',
        'last_name',
        'email',
        'status',
        'user_type',
        'time_zone',
        'country_code',
        'mobile_number',
        'last_login_datetime',
        'last_login_ip',
        'profile_photo_path',
        'password',
        'created_at',
        'updated_at',
        
        
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'date_added' => 'datetime',
        'last_update_datetime' => 'datetime',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('uploads/user_profile_image/' . $this->profile_photo_path);
        }

        return $this->profile_photo_path;
    }

  
}
