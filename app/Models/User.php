<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Constants\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{



    public function isTeacher(): bool
    {
        return $this->utype === UserRole::TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->utype === UserRole::STUDENT;
    }

    public function isParent(): bool
    {
        return $this->utype === UserRole::PARENT;
    }

    public function hasRole($role): bool
    {
        return $this->utype === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->utype, $roles);
    }

    public function getRoleName(): string
    {
        return UserRole::getAllRoles()[$this->utype] ?? 'Không xác định';
    }











    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'mobile',
        'utype',
        'image'
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

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function parent()
    {
        return $this->hasOne(Parent::class, 'user_id', 'id');
    }









}
