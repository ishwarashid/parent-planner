<?php

namespace App\Http\Controllers;

use App\Models\HelpVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HelpController extends Controller
{
    public function index(Request $request)
    {
        // Get all active help videos ordered by custom order field and then by creation date
        $videos = HelpVideo::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(6); // 6 videos per page

        return view('help', [
            'videos' => $videos,
            'currentPage' => $videos->currentPage(),
            'lastPage' => $videos->lastPage(),
        ]);
    }
    
    public function getVideoUrl($id)
    {
        $video = HelpVideo::find($id);
        
        if (!$video || !$video->video_url) {
            return response()->json(['error' => 'Video not found'], 404);
        }
        
        // Get the video URL - this will now use the updated accessor which handles CDN properly
        $videoUrl = $video->video_url; // This will trigger the accessor method
        
        if (empty($videoUrl)) {
            return response()->json(['error' => 'Video URL not available'], 404);
        }
        
        // For help videos that are public, the accessor will return the CDN URL directly
        return response()->json(['url' => $videoUrl]);
    }
    
    public function proxyVideo($filename)
    {
        // Validate filename to prevent path traversal attacks
        if (preg_match('/\.\.\//', $filename) || strpos($filename, '..\\') !== false) {
            abort(400, 'Invalid filename');
        }
        
        // Construct the CDN URL for the video
        $cdnUrl = config("filesystems.disks.do-public.cdn_url") . "/help-videos/{$filename}";
        
        // Get range headers from the request to support seeking
        $range = request()->header('Range');
        
        // Create a stream context with the range header if present
        $contextOptions = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: Laravel Video Proxy'
                ],
                'timeout' => 30
            ]
        ];
        
        if ($range) {
            $contextOptions['http']['header'][] = "Range: {$range}";
        }
        
        $context = stream_context_create($contextOptions);
        
        // Open stream to CDN
        $stream = fopen($cdnUrl, 'r', false, $context);
        
        if (!$stream) {
            abort(404, 'Video not found');
        }
        
        // Get stream metadata to detect response code and headers
        $meta = stream_get_meta_data($stream);
        $wrapper = $meta['wrapper_data'] ?? [];
        
        // Parse the response headers to get status and other headers
        $status = 200;
        $headers = [];
        
        foreach ($wrapper as $line) {
            if (strpos($line, 'HTTP/') === 0) {
                if (strpos($line, '206') !== false) {
                    $status = 206; // Partial content
                } else if (strpos($line, '404') !== false) {
                    fclose($stream);
                    abort(404, 'Video not found');
                } else if (strpos($line, '403') !== false) {
                    fclose($stream);
                    abort(403, 'Access denied');
                }
            } elseif (strpos($line, 'Content-Type:') === 0) {
                // Extract and preserve content-type
                $contentType = trim(substr($line, 13));
                // Use a default if CDN doesn't provide content-type for video
                if (strtolower(substr($contentType, 0, 5)) === 'video' || 
                    strpos($contentType, 'mp4') !== false) {
                    $headers['Content-Type'] = $contentType;
                } else {
                    $headers['Content-Type'] = 'video/mp4';
                }
            } elseif (strpos($line, 'Content-Length:') === 0) {
                $headers['Content-Length'] = trim(substr($line, 15));
            } elseif (strpos($line, 'Accept-Ranges:') === 0) {
                $headers['Accept-Ranges'] = trim(substr($line, 14));
            } elseif (strpos($line, 'Content-Range:') === 0) {
                $headers['Content-Range'] = trim(substr($line, 14));
            }
        }
        
        // Set default headers if not provided by CDN
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'video/mp4';
        }
        
        if (!isset($headers['Accept-Ranges'])) {
            $headers['Accept-Ranges'] = 'bytes';
        }
        
        // Add CORS headers
        $headers['Access-Control-Allow-Origin'] = '*';
        $headers['Access-Control-Allow-Methods'] = 'GET, HEAD, OPTIONS';
        $headers['Access-Control-Allow-Headers'] = 'Range, Content-Type';
        
        // Create and return a streamed response
        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, $status, $headers);
    }
}
