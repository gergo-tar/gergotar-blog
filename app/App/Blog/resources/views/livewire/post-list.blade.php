<div>
    @foreach ($posts as $post)
        <livewire:blog.post-item :post="$post" :key="$post->id" />
    @endforeach

    @if ($isPaginated)
        {{ $posts->links() }}
    @endif
</div>
