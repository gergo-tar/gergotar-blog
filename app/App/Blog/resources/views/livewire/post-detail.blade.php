<article>
    <div class="pt-8 lg:pt-16">
        <div class="pb-8 border-b border-grey-lighter sm:pb-12">
            <span class="inline-block px-2 py-1 mb-5 text-sm rounded-full bg-green-light font-body text-green sm:mb-8">
                {{ ucwords($post->category->translation->name) }}
            </span>
            <h1
                class="block text-3xl font-semibold leading-tight font-body text-primary dark:text-white sm:text-4xl md:text-5xl">
                {{ $post->translation->title }}
            </h1>
            <div class="flex flex-col pt-5 sm:flex-row sm:items-center sm:pt-8">
                <p class="font-light font-body text-primary dark:text-white">
                    {{ $post->published_at_formatted }}
                </p>
                <p class="mt-4 font-light font-body text-primary dark:text-white sm:mt-0">
                    <span class="dark:text-white font-body text-grey sm:pl-2">//</span>
                    {{ $post->translation->reading_time_string }}
                </p>
            </div>
            {{-- Tags --}}
            <div class="flex flex-wrap">
                @foreach ($post->tags as $tag)
                    <span
                        class="inline-block px-2 py-1 mt-4 mb-4 text-sm rounded-full bg-yellow-light font-body text-yellow-dark dark:text-green-dark @if ($loop->first) mr-1 @else mx-1 @endif">
                        {{ $tag->translation->name }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- Featured image --}}
        @if ($post->relationLoaded('featuredImage') && $post->featured_image_url)
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt }}"
                class="object-cover w-full my-4 rounded-sm max-h-96">
        @endif
        {{-- TOC --}}
        @if ($post->translation->toc)
            <div class="mt-8 prose border-b max-w-none border-grey-lighter dark:prose-dark sm:mt-12" id="toc">
                <h2 class="text-lg font-semibold font-body text-primary dark:text-white">
                    {{ __('Blog::post.toc') }}
                </h2>
                <div class="toc">
                    {!! $post->translation->toc !!}
                </div>
            </div>
        @endif
        {{-- Content --}}
        <div class="py-8 prose border-b max-w-none border-grey-lighter dark:prose-dark sm:py-12">
            {!! tiptap_converter()->asHtml($post->translation->content) !!}
        </div>

        {{-- Donate button --}}
        @if (config('services.stripe.donate_link'))
            <div class="py-8 text-center sm:py-10">
                <p class="mb-4 text-sm font-light font-body text-primary dark:text-white">
                    @lang('donate.description')
                </p>
                <div
                    class="block px-5 py-2 text-xs font-semibold text-center text-white transition-colors bg-secondary font-body hover:bg-green sm:inline-block sm:text-left sm:text-sm hover:cursor-pointer">
                    <a href="{{ config('services.stripe.donate_link') }}" target="_blank">
                        @lang('donate.button')
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Notification -->
    <div aria-live="assertive" id="copy-code-notification"
        class="fixed inset-0 z-30 items-end hidden py-6 transition duration-300 ease-out transform pointer-events-none sm:px-4 sm:items-start sm:p-6">
        <div class="flex flex-col items-center w-full space-y-2 sm:items-end">
            <div class="z-10 flex items-center w-full p-4 bg-white rounded-lg shadow sm:max-w-xs text-primary"
                role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
                    <svg class="w-6 h-6 text-green" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="text-sm font-normal ms-3">{{ __('actions.copied_to_clipboard') }}</div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-primary rounded-lg focus:ring-2 focus:ring-primary p-1.5 inline-flex items-center justify-center h-8 w-8 hover:cursor-pointer pointer-events-auto"
                    aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</article>

@script
    <script>
        Livewire.hook('component.init', ({
            component,
            cleanup
        }) => {
            let timeoutId; // Global variable to store the timeout ID
            let codeBlocks = document.querySelectorAll('pre code'); // Select all code blocks under <pre> tags

            const getNotification = () => {
                return document.getElementById('copy-code-notification');
            }

            const showCopiedContentNotification = () => {
                let notification = getNotification();

                if (!notification) {
                    return;
                }

                // Add flex class if it is not present
                if (!notification.classList.contains('flex')) {
                    notification.classList.add('flex');
                }

                // Remove hidden class if it is present
                if (notification.classList.contains('hidden')) {
                    notification.classList.remove('hidden');
                }

                // Remove Entering transition classes
                notification.classList.remove('transform', 'ease-out', 'duration-300');
                // Add Leaving transition classes
                notification.classList.add('ease-in', 'duration-100');
            }

            const removeCopiedContentNotification = () => {
                let notification = getNotification();

                if (!notification) {
                    return;
                }

                // Add hidden class if it is not present
                if (!notification.classList.contains('hidden')) {
                    notification.classList.add('hidden');
                }

                // Remove flex class if it is present
                if (notification.classList.contains('flex')) {
                    notification.classList.remove('flex');
                }

                // Remove Leaving transition classes
                notification.classList.remove('ease-in', 'duration-100');
                // Add Entering transition classes
                notification.classList.add('transform', 'ease-out', 'duration-300');
            }

            if (getNotification()) {
                getNotification().querySelector('button').addEventListener('click', function() {
                    removeCopiedContentNotification();
                });
            }

            codeBlocks.forEach(function(originalCodeBlock) {
                // Get the class of the original <code> block
                let codeClass = originalCodeBlock.className;

                // If it already has the 'code-formated' class, skip it
                if (originalCodeBlock.classList.contains('code-formated')) {
                    return;
                }

                // Create a wrapper div element
                let wrapper = document.createElement('div');
                // Make the wrapper relative for absolute positioning of the button
                wrapper.classList.add('relative', 'group');

                // Create a button for copying, styled and positioned in the top-right corner
                let copyButton = document.createElement('button');
                copyButton.innerHTML = `<svg class="w-5 h-5 text-white transition lg:text-gray-500 shrink-0 lg:group-hover:text-white copy-button hover:cursor-pointer"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"></path>
                        <path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z"></path>
                    </svg>`;
                copyButton.classList.add('absolute', 'top-3', 'right-3', 'lg:opacity-25',
                    'lg:group-hover:opacity-100', 'transition-opacity'); // Position top-right

                // Append the original <pre> block's parent (which should be the <pre> tag) to the wrapper
                originalCodeBlock.parentNode.parentNode.insertBefore(wrapper, originalCodeBlock.parentNode);

                // Move the original <pre> block inside the wrapper
                wrapper.appendChild(originalCodeBlock.parentNode);

                // Add the copy button to the wrapper
                wrapper.appendChild(copyButton);

                // Add the class to mark it as formatted (so it won't be processed again)
                originalCodeBlock.classList.add('code-formated');

                // Add copy-to-clipboard functionality
                copyButton.addEventListener('click', function() {
                    // Copy code content to clipboard
                    navigator.clipboard.writeText(originalCodeBlock.textContent.trim()).then(
                        function() {
                            showCopiedContentNotification();
                            // Clear the existing timeout, if any
                            if (timeoutId) {
                                clearTimeout(timeoutId);
                            }
                            // Set a new timeout to remove the notification after 3 seconds
                            timeoutId = setTimeout(function() {
                                // Only remove it if it is still visible
                                let notification = getNotification();
                                if (notification && !notification.classList.contains(
                                        'hidden')) {
                                    removeCopiedContentNotification();
                                }
                            }, 3000); // Notification visible for 3 seconds
                        },
                        function(err) {
                            console.error('Could not copy text: ', err);
                        });
                });
            });

            // Get all video elements where the autoplay="true" attribute is set
            const videos = document.querySelectorAll('video[autoplay]');

            // Create an IntersectionObserver instance
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    const video = entry.target;

                    // If the video is in view, play it
                    if (entry.isIntersecting) {
                        video.play();
                    } else {
                        video.pause(); // Pause the video if it's out of view
                    }
                });
            }, {
                threshold: 0.5 // Video will start when 50% of it is visible
            });

            // Observe each video
            videos.forEach(video => {
                observer.observe(video);
            });
        });
    </script>
@endscript
