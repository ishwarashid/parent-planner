<x-app-layout>
    <style>
        :root {
            --theme-dark-navy: #000033;
            --theme-turquoise: #40E0D0;
            --theme-turquoise-light: rgba(64, 224, 208, 0.15);
            --theme-navy-light: rgba(0, 0, 51, 0.05);
        }

        /* ---------- Header ---------- */
        .theme-header-text {
            color: var(--theme-dark-navy);
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        /* ---------- Search Bar ---------- */
        .theme-search-wrapper {
            margin-bottom: 3rem; /* Adds clean space before video cards */
        }

        .theme-search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .theme-search {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem; /* more left padding for icon */
            background-color: var(--theme-navy-light);
            border: 2px solid rgba(0, 0, 51, 0.15);
            border-radius: 0.75rem;
            transition: all 0.25s ease-in-out;
            font-size: 1rem;
        }

        .theme-search:focus {
            border-color: var(--theme-turquoise);
            box-shadow: 0 0 0 4px rgba(64, 224, 208, 0.35);
        }

        .theme-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            color: rgba(0, 0, 51, 0.6);
            pointer-events: none;
        }


        /* ---------- Video Card ---------- */
        .theme-video-card {
            background-color: var(--theme-turquoise-light);
            border: 1px solid rgba(64, 224, 208, 0.3);
            border-radius: 1rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-top: 2rem;
            margin-bottom: 2rem;
            cursor: pointer;
        }

        .theme-video-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 25px rgba(0, 0, 51, 0.15);
        }

        .theme-thumbnail {
            background: linear-gradient(145deg, rgba(0, 0, 51, 0.05), rgba(64, 224, 208, 0.08));
            border-bottom: 1px solid rgba(64, 224, 208, 0.2);
            height: 12rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .theme-thumbnail i {
            font-size: 3.5rem;
            color: rgba(0, 0, 51, 0.5);
            transition: color 0.3s ease;
        }

        .theme-video-card:hover .theme-thumbnail i {
            color: var(--theme-dark-navy);
        }

        .theme-duration {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: var(--theme-turquoise);
            color: rgba(0, 0, 51, 0.85);
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        /* ---------- Text Styles ---------- */
        .theme-video-title {
            color: var(--theme-dark-navy);
            font-weight: 700;
        }

        .theme-video-description {
            color: #002b40;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .theme-video-date {
            color: rgba(0, 0, 51, 0.7);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
        }

        /* ---------- Pagination ---------- */
        .theme-pagination nav {
            display: flex;
            justify-content: center;
        }

        .theme-pagination nav > .hidden {
            flex-direction: column-reverse;
        }

        .theme-pagination .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .theme-pagination .page-item {
            list-style: none;
        }

        .theme-pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: var(--theme-turquoise);
            color: var(--theme-dark-navy);
            border: 1px solid rgba(64, 224, 208, 0.4);
            font-weight: 600;
            transition: all 0.25s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 51, 0.1);
        }

        .theme-pagination .page-link:hover {
            background-color: var(--theme-dark-navy);
            color: var(--theme-turquoise);
            border-color: var(--theme-turquoise);
            box-shadow: 0 4px 10px rgba(0, 0, 51, 0.15);
            transform: translateY(-2px);
        }

        .theme-pagination .active .page-link {
            background-color: var(--theme-dark-navy) !important;
            color: var(--theme-turquoise) !important;
            border-color: var(--theme-turquoise) !important;
            box-shadow: 0 4px 12px rgba(64, 224, 208, 0.3);
            pointer-events: none;
        }

        .theme-pagination .disabled .page-link {
            background-color: rgba(64, 224, 208, 0.1);
            color: rgba(0, 0, 51, 0.4);
            cursor: not-allowed;
            border-color: rgba(64, 224, 208, 0.2);
            box-shadow: none;
        }

        /* Video Modal Styles */
        .video-modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .video-modal-content {
            background-color: #1a202c;
            margin: 2% auto;
            padding: 0;
            border: none;
            border-radius: 0.5rem;
            width: 80%;
            max-width: 800px;
            position: relative;
        }

        .video-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #2d3748;
        }

        .video-modal-title {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .close-video-modal {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .close-video-modal:hover {
            background-color: #4a5568;
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        }

        .video-player {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000; /* Black background while loading */
        }

        .video-modal-body {
            padding: 1.5rem;
        }

        .video-description {
            color: #cbd5e0;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .video-modal-content {
                width: 95%;
                margin: 5% auto;
            }
            
            .video-modal-header {
                padding: 0.75rem 1rem;
            }
            
            .video-modal-body {
                padding: 1rem;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-bold text-2xl theme-header-text leading-tight tracking-wide">
            {{ __('Help Center') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Search Bar -->
            <div class="mb-10">
                <div class="relative">
                    <input 
                        type="text" 
                        id="video-search" 
                        placeholder="Search help videos..." 
                        class="w-full p-4 pl-12 rounded-xl theme-search focus:outline-none"
                    >
                </div>
            </div>

            <!-- Video Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($videos as $video)
                    <div class="{{ $video->hasValidVideo() ? 'theme-video-card video-item cursor-pointer' : 'theme-video-card opacity-60' }}" 
                         @if($video->hasValidVideo()) 
                         data-video-id="{{ $video->id }}" 
                         data-title="{{ $video->title }}" 
                         data-description="{{ $video->description }}"
                         data-thumbnail="{{ $video->thumbnail_url }}"
                         @endif>
                        <div class="theme-thumbnail">
                            @if($video->thumbnail_url)
                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <i class="bi bi-play-circle"></i>
                            @endif
                            <span class="theme-duration">{{ $video->duration }}</span>
                        </div>
                        <div class="p-6">
                            <h3 class="theme-video-title text-lg mb-2">{{ $video->title }}</h3>
                            <p class="theme-video-description mb-4">{{ $video->description }}</p>
                            <div class="theme-video-date">
                                <i class="bi bi-calendar me-2"></i>
                                <span>{{ $video->date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 theme-pagination">
                {{ $videos->links() }}
            </div>
        </div>
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <div class="video-modal-header">
                <h3 id="modalVideoTitle" class="video-modal-title"></h3>
                <button class="close-video-modal">&times;</button>
            </div>
            <div class="video-container">
                <video id="videoPlayer" class="video-player" controls preload="none" poster="" crossorigin="anonymous">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="video-modal-body">
                <p id="modalVideoDescription" class="video-description"></p>
            </div>
        </div>
    </div>

    <script>
        // Smooth search filtering
        document.getElementById('video-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.theme-video-card');

            cards.forEach(card => {
                const title = card.querySelector('.theme-video-title').textContent.toLowerCase();
                const description = card.querySelector('.theme-video-description').textContent.toLowerCase();
                card.style.display = (title.includes(searchTerm) || description.includes(searchTerm)) ? '' : 'none';
            });
        });

        // Video modal functionality
        const videoItems = document.querySelectorAll('.video-item');
        const videoModal = document.getElementById('videoModal');
        const modalVideoTitle = document.getElementById('modalVideoTitle');
        const modalVideoDescription = document.getElementById('modalVideoDescription');
        const videoPlayer = document.getElementById('videoPlayer');
        const closeBtn = document.querySelector('.close-video-modal');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        videoItems.forEach(item => {
            item.addEventListener('click', function() {
                const videoId = this.getAttribute('data-video-id');
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                
                if (videoId) {
                    // Show loading state
                    modalVideoTitle.textContent = title;
                    modalVideoDescription.textContent = 'Loading video...';
                    videoModal.style.display = 'block';
                    
                    // Fetch fresh video URL via AJAX
                    fetch(`/help/video/${videoId}/url`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.url) {
                            modalVideoDescription.textContent = description; // Restore description
                            // Loading indicator is already shown by default
                            
                            // Extract filename from the CDN URL and use the proxy route
                            const urlParts = data.url.split("/");
                            const filename = urlParts.pop(); // Get the last part which should be the filename
                            
                            // Use the proxy route to bypass CORS
                            videoPlayer.src = `/video-proxy/${filename}`;
                            
                            // Clear any previous error handlers
                            videoPlayer.onerror = null;
                            

                            
                            // Handle video loading errors including CORS
                            videoPlayer.onerror = function(event) {
                                document.getElementById('videoLoadingIndicator').classList.add('hidden');
                                console.error('Video error:', event.target.error);
                                
                                // Check if this is a CORS-related error
                                if (event.target.error) {
                                    // MEDIA_ERR_SRC_NOT_SUPPORTED - may be CORS related
                                    modalVideoDescription.textContent = 'Video cannot be loaded. This may be a CORS issue.';
                                } else {
                                    modalVideoDescription.textContent = 'Error loading video. Please try again.';
                                }
                            };
                            
                            // Show play button when video can be played
                            videoPlayer.oncanplay = function() {
                                document.getElementById('videoLoadingIndicator').classList.add('hidden');
                            };
                            
                            // Show loading indicator when waiting for data
                            videoPlayer.onwaiting = function() {
                                document.getElementById('videoLoadingIndicator').classList.remove('hidden');
                            };
                            
                            // Hide loading indicator when data is available
                            videoPlayer.oncanplaythrough = function() {
                                document.getElementById('videoLoadingIndicator').classList.add('hidden');
                            };
                            
                            // Preload metadata when modal opens to speed up play
                            videoPlayer.preload = 'metadata';
                            videoPlayer.load();
                        } else {
                            document.getElementById('videoLoadingIndicator').classList.add('hidden');
                            modalVideoDescription.textContent = data.error || 'Error loading video.';
                            console.error('Error loading video:', data.error);
                        }
                    })
                    .catch(error => {
                        document.getElementById('videoLoadingIndicator').classList.add('hidden');
                        modalVideoDescription.textContent = 'Error loading video.';
                        console.error('Fetch error:', error);
                    });
                } else {
                    alert('This video is not yet available.');
                }
            });
        });

        // Close modal when clicking the X
        closeBtn.addEventListener('click', function() {
            videoModal.style.display = 'none';
            videoPlayer.pause();
            videoPlayer.src = ''; // Clear the source to stop buffering
        });

        // Close modal when clicking outside the modal content
        window.addEventListener('click', function(event) {
            if (event.target === videoModal) {
                videoModal.style.display = 'none';
                videoPlayer.pause();
                videoPlayer.src = ''; // Clear the source to stop buffering
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && videoModal.style.display === 'block') {
                videoModal.style.display = 'none';
                videoPlayer.pause();
                videoPlayer.src = ''; // Clear the source to stop buffering
            }
        });
    </script>
</x-app-layout>