@extends('layouts.blog')

@section('title', 'Blog - Parent Planner')

@section('content')
<!-- ======= Hero =======-->
<section class="hero__v6">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center mb-5">
        <h1 class="display-4 fw-bold">Parenting Blog</h1>
        <p class="lead">Tips, advice, and resources for modern parents</p>
      </div>
    </div>
  </div>
</section>
<!-- End Hero-->

<div class="container py-5">
    @if($blogs->count() > 0)
        <div class="row g-4">
            @foreach($blogs as $blog)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        @if($blog->featured_image)
                            <div class="position-relative" style="height: 200px;">
                                <img src="{{ Storage::disk('do')->temporaryUrl($blog->featured_image, now()->addMinutes(5)) }}" class="card-img-top" alt="{{ $blog->title }}" style="height: 100%; object-fit: cover;">
                                @if($blog->video_url)
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger rounded-pill">
                                            <i class="bi bi-camera-video"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center position-relative" style="height: 200px;">
                                <i class="bi bi-image fs-1 text-muted"></i>
                                @if($blog->video_url)
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger rounded-pill">
                                            <i class="bi bi-camera-video"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title mb-3">{{ $blog->title }}</h5>
                            @if($blog->excerpt)
                                <p class="card-text flex-grow-1 text-muted mb-4">{{ $blog->excerpt }}</p>
                            @endif
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-primary mt-auto rounded-pill px-4 py-2">
                                Read More
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-4 px-4">
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-calendar me-1"></i>
                                <span>{{ $blog->published_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                {{ $blogs->links() }}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12 text-center py-5">
                <div class="alert alert-info rounded-4" role="alert">
                    <h4 class="alert-heading">No blog posts available yet</h4>
                    <p class="mb-0">Check back soon for helpful parenting tips and resources!</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
