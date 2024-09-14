<article class="py-6 border-b border-grey-lighter ">
    {{-- TODO: link to category page --}}
    <span
      class="inline-block px-2 py-1 mb-4 text-sm rounded-full bg-green-light font-body text-green"
      >{{ $post->category->translation->name }}</span
    >
    {{-- Featured image --}}
    @if ($post->relationLoaded('featuredImage') && $post->featured_image_url)
    <a href="{{ $post->translation->url }}">
        <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt }}" class="object-cover w-full h-56 rounded-t-md" />
    </a>
    @endif
    {{-- Title --}}
    <a href="{{ $post->translation->url }}" class="block mt-4 text-lg font-semibold transition-colors font-body text-primary hover:text-green dark:text-white dark:hover:text-secondary" wire:navigate>
        {{ $post->translation->title }}
    </a>
    {{-- Excerpt --}}
    <p class="text-gray-800 dark:text-grey-lighter">
        <a href="{{ $post->translation->url }}">
            {{ $post->translation->excerpt }}
        </a>
    </p>
    <div class="flex items-center pt-4">
      <p class="pr-2 font-light font-body text-primary dark:text-white">
        {{ $post->published_at_formatted }}
      </p>
      <span class="font-body text-grey dark:text-white">//</span>
      <p class="pl-2 font-light font-body text-primary dark:text-white">
        {{ $post->translation->reading_time_string }}
      </p>
    </div>
  </article>
