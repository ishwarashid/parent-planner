<?php

namespace App\Http\Controllers;

use App\Models\LandingPageVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminLandingPageVideoController extends Controller
{
    public function index()
    {
        $video = LandingPageVideo::orderBy('created_at', 'desc')->first(); // Only one landing page video
        return view('admin.landing-page-videos.index', compact('video'));
    }

    public function create()
    {
        return view('admin.landing-page-videos.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|regex:/^[0-9]{1,2}:[0-9]{2}$/|nullable', // Format: MM:SS
            'date' => 'nullable|date',
            'video' => 'nullable|file|mimes:mp4,mov,avi,mkv,webm|max:102400', // Max 100MB, added webm format
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Max 10MB
            'is_active' => 'boolean'
        ];

        $messages = [
            'video.file' => 'The video must be a file.',
            'video.mimes' => 'The video must be a file of type: :values.',
            'video.max' => 'The video may not be greater than :max kilobytes.',
            'thumbnail.image' => 'The thumbnail must be an image.',
            'thumbnail.mimes' => 'The thumbnail must be a file of type: :values.',
            'thumbnail.max' => 'The thumbnail may not be greater than :max kilobytes.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            \Log::info('Validation failed', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle file uploads if provided
        $videoUrl = null;
        $thumbnailUrl = null;

        if ($request->hasFile('video')) {
            \Log::info('Processing video upload', [
                'file_name' => $request->file('video')->getClientOriginalName(),
                'file_size' => $request->file('video')->getSize(),
                'file_mime' => $request->file('video')->getMimeType(),
            ]);

            try {
                $videoUrl = $request->file('video')->store('landing-page-videos', 'do-public');
                \Log::info('Video successfully stored in DO Spaces', ['path' => $videoUrl]);
            } catch (\Exception $e) {
                \Log::error('DO Spaces video upload failed: ' . $e->getMessage());
                // Fallback to public disk if DO Spaces fails
                try {
                    $videoUrl = $request->file('video')->store('landing-page-videos', 'public');
                    \Log::info('Video successfully stored in public disk', ['path' => $videoUrl]);
                } catch (\Exception $fallbackException) {
                    \Log::error('Fallback video upload also failed: ' . $fallbackException->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'The video failed to upload. Error: ' . $fallbackException->getMessage());
                }
            }
        }

        if ($request->hasFile('thumbnail')) {
            \Log::info('Processing thumbnail upload', [
                'file_name' => $request->file('thumbnail')->getClientOriginalName(),
                'file_size' => $request->file('thumbnail')->getSize(),
                'file_mime' => $request->file('thumbnail')->getMimeType(),
            ]);

            try {
                $thumbnailUrl = $request->file('thumbnail')->store('landing-page-video-thumbnails', 'do-public');
                \Log::info('Thumbnail successfully stored in DO Spaces', ['path' => $thumbnailUrl]);
            } catch (\Exception $e) {
                \Log::error('DO Spaces thumbnail upload failed: ' . $e->getMessage());
                // Fallback to public disk if DO Spaces fails
                try {
                    $thumbnailUrl = $request->file('thumbnail')->store('landing-page-video-thumbnails', 'public');
                    \Log::info('Thumbnail successfully stored in public disk', ['path' => $thumbnailUrl]);
                } catch (\Exception $fallbackException) {
                    \Log::error('Fallback thumbnail upload also failed: ' . $fallbackException->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'The thumbnail failed to upload. Error: ' . $fallbackException->getMessage());
                }
            }
        }

        LandingPageVideo::create([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
            'date' => $request->date,
            'video_url' => $videoUrl,
            'thumbnail_url' => $thumbnailUrl,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.landing-page-videos.index')->with('success', 'Landing page video added successfully.');
    }

    public function edit(LandingPageVideo $landingPageVideo)
    {
        return view('admin.landing-page-videos.edit', compact('landingPageVideo'));
    }

    public function update(Request $request, LandingPageVideo $landingPageVideo)
    {
        \Log::info('Update request received', $request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|regex:/^[0-9]{1,2}:[0-9]{2}$/|nullable', // Format: MM:SS
            'date' => 'nullable|date',
            'video' => 'nullable|file|mimes:mp4,mov,avi,mkv,webm|max:102400', // Max 100MB, added webm format
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Max 10MB
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            \Log::info('Update validation failed', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle file uploads if provided
        $videoUrl = $landingPageVideo->video_url;
        $thumbnailUrl = $landingPageVideo->thumbnail_url;

        // Delete old files if new ones are uploaded
        if ($request->hasFile('video')) {
            $isDoSpacesFile = !filter_var($landingPageVideo->video_url, FILTER_VALIDATE_URL) &&
                             strpos($landingPageVideo->video_url, 'digitaloceanspaces') === false;

            if ($landingPageVideo->video_url && $isDoSpacesFile) {
                // Determine which disk the file is on
                $disk = Storage::disk('do')->exists($landingPageVideo->video_url) ? 'do' : 'public';
                if (Storage::disk($disk)->exists($landingPageVideo->video_url)) {
                    try {
                        Storage::disk($disk)->delete($landingPageVideo->video_url);
                        \Log::info('Old video file deleted', ['path' => $landingPageVideo->video_url, 'disk' => $disk]);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old video file: ' . $e->getMessage());
                    }
                }
            }

            \Log::info('Processing video update upload', [
                'file_name' => $request->file('video')->getClientOriginalName(),
                'file_size' => $request->file('video')->getSize(),
                'file_mime' => $request->file('video')->getMimeType(),
            ]);

            try {
                $videoUrl = $request->file('video')->store('landing-page-videos', 'do-public');
                \Log::info('Updated video successfully stored in DO Spaces', ['path' => $videoUrl]);
            } catch (\Exception $e) {
                \Log::error('DO Spaces video update upload failed: ' . $e->getMessage());
                // Fallback to public disk if DO Spaces fails
                try {
                    $videoUrl = $request->file('video')->store('landing-page-videos', 'public');
                    \Log::info('Updated video successfully stored in public disk', ['path' => $videoUrl]);
                } catch (\Exception $fallbackException) {
                    \Log::error('Fallback video update upload also failed: ' . $fallbackException->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'The video failed to upload. Error: ' . $fallbackException->getMessage());
                }
            }
        }

        if ($request->hasFile('thumbnail')) {
            $isDoSpacesFile = !filter_var($landingPageVideo->thumbnail_url, FILTER_VALIDATE_URL) &&
                             strpos($landingPageVideo->thumbnail_url, 'digitaloceanspaces') === false;

            if ($landingPageVideo->thumbnail_url && $isDoSpacesFile) {
                // Determine which disk the file is on
                $disk = Storage::disk('do')->exists($landingPageVideo->thumbnail_url) ? 'do' : 'public';
                if (Storage::disk($disk)->exists($landingPageVideo->thumbnail_url)) {
                    try {
                        Storage::disk($disk)->delete($landingPageVideo->thumbnail_url);
                        \Log::info('Old thumbnail file deleted', ['path' => $landingPageVideo->thumbnail_url, 'disk' => $disk]);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old thumbnail file: ' . $e->getMessage());
                    }
                }
            }

            \Log::info('Processing thumbnail update upload', [
                'file_name' => $request->file('thumbnail')->getClientOriginalName(),
                'file_size' => $request->file('thumbnail')->getSize(),
                'file_mime' => $request->file('thumbnail')->getMimeType(),
            ]);

            try {
                $thumbnailUrl = $request->file('thumbnail')->store('landing-page-video-thumbnails', 'do-public');
                \Log::info('Updated thumbnail successfully stored in DO Spaces', ['path' => $thumbnailUrl]);
            } catch (\Exception $e) {
                \Log::error('DO Spaces thumbnail update upload failed: ' . $e->getMessage());
                // Fallback to public disk if DO Spaces fails
                try {
                    $thumbnailUrl = $request->file('thumbnail')->store('landing-page-video-thumbnails', 'public');
                    \Log::info('Updated thumbnail successfully stored in public disk', ['path' => $thumbnailUrl]);
                } catch (\Exception $fallbackException) {
                    \Log::error('Fallback thumbnail update upload also failed: ' . $fallbackException->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'The thumbnail failed to upload. Error: ' . $fallbackException->getMessage());
                }
            }
        }

        $landingPageVideo->update([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
            'date' => $request->date,
            'video_url' => $videoUrl,
            'thumbnail_url' => $thumbnailUrl,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.landing-page-videos.index')->with('success', 'Landing page video updated successfully.');
    }

    public function destroy(LandingPageVideo $landingPageVideo)
    {
        // Delete associated files if they exist
        if ($landingPageVideo->video_url) {
            // Check if it's a local/DO Spaces file (doesn't contain external URL)
            if (!filter_var($landingPageVideo->video_url, FILTER_VALIDATE_URL) &&
                strpos($landingPageVideo->video_url, 'digitaloceanspaces') === false) {
                try {
                    // Try DO disk first
                    if (Storage::disk('do')->exists($landingPageVideo->video_url)) {
                        Storage::disk('do')->delete($landingPageVideo->video_url);
                    } else if (Storage::disk('public')->exists($landingPageVideo->video_url)) {
                        // Fallback to public disk
                        Storage::disk('public')->delete($landingPageVideo->video_url);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to delete video file: ' . $e->getMessage());
                    // Continue with deletion even if file deletion fails
                }
            }
        }

        if ($landingPageVideo->thumbnail_url) {
            // Check if it's a local/DO Spaces file (doesn't contain external URL)
            if (!filter_var($landingPageVideo->thumbnail_url, FILTER_VALIDATE_URL) &&
                strpos($landingPageVideo->thumbnail_url, 'digitaloceanspaces') === false) {
                try {
                    // Try DO disk first
                    if (Storage::disk('do')->exists($landingPageVideo->thumbnail_url)) {
                        Storage::disk('do')->delete($landingPageVideo->thumbnail_url);
                    } else if (Storage::disk('public')->exists($landingPageVideo->thumbnail_url)) {
                        // Fallback to public disk
                        Storage::disk('public')->delete($landingPageVideo->thumbnail_url);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to delete thumbnail file: ' . $e->getMessage());
                    // Continue with deletion even if file deletion fails
                }
            }
        }

        $landingPageVideo->delete();
        return redirect()->route('admin.landing-page-videos.index')->with('success', 'Landing page video deleted successfully.');
    }
}
