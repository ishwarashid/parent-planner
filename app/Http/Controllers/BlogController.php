<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs.
     */
    public function index()
    {
        $blogs = Blog::where('published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(6);
            
        return view('blogs.index', compact('blogs'));
    }

    /**
     * Display the specified blog.
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();
            
        return view('blogs.show', compact('blog'));
    }
}
