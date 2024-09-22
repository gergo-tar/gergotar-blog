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
    <div class="flex flex-col pt-4 sm:flex-row sm:items-center">
      {{-- Author --}}
      <p class="font-light font-body text-primary dark:text-white">
        {{ $post->published_at_formatted }}
      </p>
      <p class="mt-4 font-light font-body text-primary dark:text-white sm:mt-0">
        <span class="font-body text-grey dark:text-white sm:pl-2">//</span> {{ $post->translation->reading_time_string }}
      </p>
      {{-- Tags --}}
      <span class="flex flex-wrap sm:ml-4">
        @foreach ($post->tags as $tag)
        <span
          class="inline-block px-2 py-1 mx-1 mt-4 mb-4 text-sm rounded-full bg-yellow-light font-body text-yellow-dark dark:text-green-dark @if($loop->first) ml-0 @endif"
        >
          {{ $tag->translation->name }}
        </span>
        @endforeach
      </span>
    </div>
  </article>
