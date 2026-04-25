<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,HasRoles ,Notifiable ,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'birth_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
    // إذا كان المستخدم مريضاً، هذه مواعيده المحجوزة
public function patientAppointments()
{
    return $this->hasMany(Appointment::class, 'patient_id');
}

// إذا كان المستخدم طبيباً، هذه مواعيده مع المرضى
public function doctorAppointments()
{
    return $this->hasMany(Appointment::class, 'doctor_id');
}
//public function doctorAppointments()
// {
//     return $this->hasMany(Appointment::class, 'doctor_id');
// }
}
