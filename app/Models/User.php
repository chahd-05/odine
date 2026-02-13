<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function sharedLinks()
    {
        return $this->belongsToMany(Link::class)->withPivot('access_level')->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(Link::class, 'favorites')->withTimestamps();
    }

    public function hasRole($role)
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isEditor()
    {
        return $this->hasRole('editor');
    }

    public function isViewer()
    {
        return $this->hasRole('viewer');
    }

    public function getAllPermissions()
    {
        if ($this->isAdmin()) {
            return ['create', 'read', 'update', 'delete', 'manage_user', 'restore', 'force_delete'];
        } elseif ($this->isEditor()) {
            return ['create', 'read', 'update_own', 'delete_own', 'restore_own'];
        } else {
            return ['read'];
        }
    }
}
