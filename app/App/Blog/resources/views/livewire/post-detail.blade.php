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
            <div class="flex items-center pt-5 sm:pt-8">
                <p class="pr-2 font-light font-body text-primary dark:text-white">
                    {{ $post->published_at_formatted }}
                </p>
                <span class="vdark:text-white font-body text-grey">//</span>
                <p class="pl-2 font-light font-body text-primary dark:text-white">
                    {{ $post->translation->reading_time_string }}
                </p>
            </div>
        </div>

        {{-- Featured image --}}
        @if ($post->relationLoaded('featuredImage') && $post->featured_image_url)
        <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt }}" class="object-cover w-full my-4 rounded-sm max-h-96">
        @endif
        {{-- Content --}}
        <div class="py-8 prose border-b max-w-none border-grey-lighter dark:prose-dark sm:py-12">
            {!! $post->translation->content !!}
        </div>
    </div>

    <!-- Notification -->
    <div
        aria-live="assertive"
        id="copy-code-notification"
        class="fixed inset-0 z-30 items-end hidden py-6 transition duration-300 ease-out transform pointer-events-none sm:px-4 sm:items-start sm:p-6">
        <div class="flex flex-col items-center w-full space-y-2 sm:items-end">
            <div class="z-10 flex items-center w-full p-4 bg-white rounded-lg shadow sm:max-w-xs text-primary" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
                    <svg class="w-6 h-6 text-green" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="text-sm font-normal ms-3">{{ __('actions.copied_to_clipboard') }}</div>
                <button
                    type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-primary rounded-lg focus:ring-2 focus:ring-primary p-1.5 inline-flex items-center justify-center h-8 w-8 hover:cursor-pointer pointer-events-auto"
                    aria-label="Close"
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
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
            let codeBlocks = document.querySelectorAll('code'); // Select all code blocks

            const getNotification = () => {
                return document.getElementById('copy-code-notification');
            }

            const showCopiedContentNotification = () => {
                let notification = getNotification();

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

            getNotification().querySelector('button').addEventListener('click', function() {
                removeCopiedContentNotification();
            });

            codeBlocks.forEach(function(originalCodeBlock) {
                // Get the class of the original <code> block
                let codeClass = originalCodeBlock.className;

                // If it already has the 'code-formated' class, skip it
                if (originalCodeBlock.classList.contains('code-formated')) {
                    return;
                }

                // Create a wrapper div element
                let wrapper = document.createElement('div');
                wrapper.classList.add('relative', 'group'); // Make the wrapper relative for absolute positioning of the button

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
                copyButton.classList.add('absolute', 'top-3', 'right-3', 'lg:opacity-25', 'lg:group-hover:opacity-100', 'transition-opacity'); // Position top-right

                // Append the original <pre> block's parent (which should be the <pre> tag) to the wrapper
                originalCodeBlock.parentNode.parentNode.insertBefore(wrapper, originalCodeBlock.parentNode);

                // Move the original <pre> block inside the wrapper
                wrapper.appendChild(originalCodeBlock.parentNode);

                // Add the copy button to the wrapper
                wrapper.appendChild(copyButton);

                // Add the class to mark it as formatted (so it won't be processed again)
                originalCodeBlock.classList.add('code-formated');

                // Add copy-to-clipboard functionality
                copyButton.addEventListener('click', function () {
                    // Copy code content to clipboard
                    navigator.clipboard.writeText(originalCodeBlock.textContent.trim()).then(function() {
                        showCopiedContentNotification();
                        // Clear the existing timeout, if any
                        if (timeoutId) {
                            clearTimeout(timeoutId);
                        }
                        // Set a new timeout to remove the notification after 3 seconds
                        timeoutId = setTimeout(function() {
                            // Only remove it if it is still visible
                            let notification = getNotification();
                            if (!notification.classList.contains('hidden')) {
                                removeCopiedContentNotification();
                            }
                        }, 3000); // Notification visible for 3 seconds
                    }, function(err) {
                        console.error('Could not copy text: ', err);
                    });
                });
            });
        });
    </script>
@endscript
