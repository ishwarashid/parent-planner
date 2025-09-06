<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'uploaded_by',
        'name',
        'category',
        'file_url',
        'notes',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the URL to the document file.
     *
     * @return string
     */
    public function getFileUrlAttribute($value)
    {
        if (empty($value)) {
            return '';
        }
        
        // If the file is stored in DigitalOcean Spaces with private visibility, generate a temporary URL
        if (config('filesystems.disks.do.visibility') === 'private' && str_contains($value, 'digitaloceanspaces')) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('do')->temporaryUrl($value, now()->addMinutes(5));
            } catch (Exception $e) {
                // Fall back to the helper function if there's an error
                return do_spaces_url($value);
            }
        }
        
        return do_spaces_url($value);
    }
}
