<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'invited_by',
        'role',
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

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function visitations()
    {
        return $this->hasMany(Visitation::class, 'parent_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'payer_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    // The parent who invited this user
    public function parent()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // The users invited by this user
    public function invitedUsers()
    {
        return $this->hasMany(User::class, 'invited_by');
    }

    // Get all user IDs in the family unit
    public function getFamilyMemberIds(): array
    {
        if ($this->invited_by) {
            // This is an invited user, get the parent's family
            $parent = $this->parent;
            if (!$parent) {
                return [$this->id];
            }
            $familyIds = array_merge([$parent->id], $parent->invitedUsers->pluck('id')->all());
            return $familyIds;
        } else {
            // This is a parent user, get their own family
            $familyIds = array_merge([$this->id], $this->invitedUsers->pluck('id')->all());
            return $familyIds;
        }
    }
}
