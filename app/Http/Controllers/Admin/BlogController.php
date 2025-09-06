<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs.
     */
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request)
    {
        Log::info('Blog store request received', [
            'all_inputs' => $request->all(),
            'has_file' => $request->hasFile('featured_image'),
            'user_id' => auth()->id()
        ]);
        
        // Manually handle the published field before validation
        $publishedValue = $request->has('published') && $request->published === 'on' ? true : false;
        
        // Remove the published field from request for validation
        $requestData = $request->except('published');
        
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'excerpt' => 'nullable|string',
                'content' => 'required|string',
                'video_url' => 'nullable|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            Log::info('Blog store validation passed', ['validated_data' => $validatedData]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Blog store validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Blog store unexpected error during validation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = Blog::generateUniqueSlug($request->title);
        $blog->excerpt = $request->excerpt;
        $blog->content = $request->content;
        $blog->video_url = $request->video_url;
        $blog->published = $publishedValue;
        $blog->user_id = auth()->id();

        if ($blog->published) {
            $blog->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            try {
                $path = $request->file('featured_image')->store('blog_images', 'do');
                $blog->featured_image = $path;
                Log::info('Featured image uploaded', ['path' => $path]);
            } catch (\Exception $e) {
                Log::error('Blog featured image upload failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        try {
            $blog->save();
            Log::info('Blog saved successfully', ['blog_id' => $blog->id]);
        } catch (\Exception $e) {
            Log::error('Blog save failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        Log::info('Blog update request received', [
            'blog_id' => $blog->id,
            'all_inputs' => $request->all(),
            'has_file' => $request->hasFile('featured_image')
        ]);
        
        // Manually handle the published field before validation
        $publishedValue = $request->has('published') && $request->published === 'on' ? true : false;
        
        // Remove the published field from request for validation
        $requestData = $request->except('published');
        
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'excerpt' => 'nullable|string',
                'content' => 'required|string',
                'video_url' => 'nullable|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            Log::info('Blog update validation passed', ['validated_data' => $validatedData]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Blog update validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Blog update unexpected error during validation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        $blog->title = $request->title;
        $blog->slug = Blog::generateUniqueSlug($request->title);
        $blog->excerpt = $request->excerpt;
        $blog->content = $request->content;
        $blog->video_url = $request->video_url;
        $blog->published = $publishedValue;

        if ($blog->published && !$blog->published_at) {
            $blog->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($blog->featured_image) {
                Storage::disk('do')->delete($blog->featured_image);
            }

            try {
                $path = $request->file('featured_image')->store('blog_images', 'do');
                $blog->featured_image = $path;
                Log::info('Featured image updated', ['path' => $path]);
            } catch (\Exception $e) {
                Log::error('Blog featured image update failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        try {
            $blog->save();
            Log::info('Blog updated successfully', ['blog_id' => $blog->id]);
        } catch (\Exception $e) {
            Log::error('Blog update save failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(Blog $blog)
    {
        // Delete featured image if exists
        if ($blog->featured_image) {
            Storage::disk('do')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully.');
    }
}

