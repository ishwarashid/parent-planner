@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Blog Post</h1>
            <a href="{{ route('admin.blogs.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Blogs
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('title') border-red-500 @enderror" id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
                        <textarea class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('excerpt') border-red-500 @enderror" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('content') border-red-500 @enderror" id="content" name="content" rows="10" required>{{ old('content', $blog->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="video_url" class="block text-sm font-medium text-gray-700">Video URL (Optional)</label>
                        <input type="url" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('video_url') border-red-500 @enderror" id="video_url" name="video_url" value="{{ old('video_url', $blog->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                        @error('video_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Enter a YouTube, Vimeo, or other video URL to embed at the end of the blog post.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                        <input type="file" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('featured_image') border-red-500 @enderror" id="featured_image" name="featured_image">
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Upload an image for the blog post (max 2MB). Supported formats: JPEG, PNG, JPG, GIF, SVG.</p>
                        
                        @if($blog->featured_image)
                            <div class="mt-2">
                                <p class="text-sm font-medium text-gray-700">Current image:</p>
                                <img src="{{ Storage::disk('do')->temporaryUrl($blog->featured_image, now()->addMinutes(5)) }}" alt="{{ $blog->title }}" class="mt-1 h-32 w-32 object-cover rounded-md">
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-4 flex items-center">
                        <input type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" id="published" name="published" {{ old('published', $blog->published) ? 'checked' : '' }}>
                        <label class="ml-2 block text-sm text-gray-900" for="published">Publish this blog post</label>
                    </div>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('admin.blogs.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Blog Post
                        </button>
                    </div>
                </form>
                
                <hr class="my-6">
                
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900">Delete Blog Post</h3>
                    <p class="mt-1 text-sm text-gray-500">Deleting a blog post will permanently remove it from the system.</p>
                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog post? This action cannot be undone.')" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete Blog Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection