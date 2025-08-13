<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable, HasRoles;

    const BASIC_PLAN_IDS = [
        'price_1RvC8APOErLRYIriK6Wohwtj', // Standard Basic (Monthly)
        'price_1RvC9KPOErLRYIriXLtJHjBk', // Standard Basic (Yearly)
    ];

    const PREMIUM_PLAN_IDS = [
        'price_1RvC9oPOErLRYIrifvr2Ml7Q',
        'price_1RvCAaPOErLRYIri4yn3Ay4l'
    ];

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

    public function isPremium(): bool
    {
        // Assumes your subscription name is 'default'. Change if needed.
        return $this->subscribed('default') &&
            in_array($this->subscription('default')->stripe_price, self::PREMIUM_PLAN_IDS);
    }

    public function hasAdminCoParent(): bool
    {
        return $this->invitedUsers()->whereHas('roles', function ($query) {
            $query->where('name', 'Admin Co-Parent');
        })->exists();
    }

    public function isBasicPlan()
    {
        $subscription = $this->subscription('default');

        // Rule 1: User must have an active subscription
        if (!$subscription || !$subscription->active()) {
            return false;
        }
        return in_array($subscription->stripe_price, self::BASIC_PLAN_IDS);
    }

    public function canInvite(): bool
    {
        // // Eager load the subscription to avoid extra queries
        // $subscription = $this->subscription('default');

        // // Rule 1: User must have an active subscription
        // if (!$subscription || !$subscription->active()) {
        //     return false;
        // }

        // // Rule 2: Check if the user is on a Basic plan
        // $isBasicPlan = in_array($subscription->stripe_price, self::BASIC_PLAN_IDS);

        if ($this->isBasicPlan()) {
            // Rule 3: For Basic plans, count active invitations.
            // An active invitation is one that is 'pending', 'accepted', or 'registered'.
            // A 'rejected' invitation does not count towards the limit.
            $activeInvitationCount = $this->sentInvitations()
                ->where('status', '!=', 'rejected')
                ->count();

            // Allow inviting only if the count is less than 1
            return $activeInvitationCount < 1;
        }

        // If the plan is not Basic (i.e., it's Premium), always allow invitations.
        return true;
    }

    public function isAccountOwner(): bool
    {
        return $this->invited_by === null;
    }

    public function getAccountOwnerId(): int
    {
        return $this->isAccountOwner() ? $this->id : $this->invited_by;
    }

    public function isInvitedUser(): bool
    {
        return $this->invited_by !== null;
    }

    public function professional()
    {
        return $this->hasOne(Professional::class);
    }
}
