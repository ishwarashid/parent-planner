<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dob',
        'gender',
        'blood_type',
        'allergies',
        'primary_residence',
        'school_name',
        'school_grade',
        'profile_photo_path',
        'extracurricular_activities',
        'doctor_info',
        'emergency_contact_info',
        'special_needs',
        'other_info',
        'color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visitations()
    {
        return $this->hasMany(Visitation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($child) {
            if (empty($child->color)) {
                $child->color = self::getRandomColor();
            }
        });
    }

    public static function getRandomColor()
    {
        $colors = ['#F87171', '#FBBF24', '#34D399', '#60A5FA', '#A78BFA', '#F472B6', '#FCD34D', '#FCA5A5'];
        return $colors[array_rand($colors)];
    }
}
