@extends('layouts.blog')

@section('title', $blog->title . ' - Parent Planner')

@section('content')
<!-- ======= Hero =======-->
<section class="hero__v6">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center mb-5">
        <h1 class="display-4 fw-bold">{{ $blog->title }}</h1>
      </div>
    </div>
  </div>
</section>
<!-- End Hero-->

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <article>
                <header class="mb-5 text-center">
                    <div class="d-flex justify-content-center align-items-center text-muted mb-4">
                        <div class="d-flex align-items-center me-4">
                            <i class="bi bi-calendar me-2"></i>
                            <span>{{ $blog->published_at->format('F d, Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person me-2"></i>
                            <span>{{ $blog->user->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </header>

                @if($blog->featured_image)
                    <figure class="mb-5 rounded-4 overflow-hidden shadow-sm">
                        <img class="img-fluid w-100" src="{{ Storage::disk('do')->temporaryUrl($blog->featured_image, now()->addMinutes(5)) }}" alt="{{ $blog->title }}" style="max-height: 400px; object-fit: cover;">
                    </figure>
                @endif

                <section class="mb-5 fs-5 lh-lg">
                    {!! nl2br(e($blog->content)) !!}
                </section>
                
                @if($blog->video_url)
                    @php
                        $embedUrl = getVideoEmbedUrl($blog->video_url);
                    @endphp
                    @if($embedUrl)
                        <section class="mt-5 p-4 bg-light rounded-4">
                            <h3 class="mb-4 text-center">
                                <i class="bi bi-camera-video me-2"></i>
                                Watch Related Video
                            </h3>
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow">
                                <iframe src="{{ $embedUrl }}" title="Video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </section>
                    @endif
                @endif
            </article>

            <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                    <i class="bi bi-arrow-left me-1"></i> Back to Blog
                </a>
            </div>
        </div>
    </div>
</div>
@endsection