@extends("layout.base")

@section("main")
<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
    {{-- post --}}
    <article class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white mb-6">
        <h1 class="text-2xl font-semibold text-black mb-2">{{$post->title}}</h1>
        <p class="text-xs text-gray-400 mb-4">{{$post->address}}</p>
        <p class="text-sm text-gray-700 leading-relaxed mb-4">{{$post->content}}</p>
        <div class="flex items-center gap-4 pt-3 border-t border-gray-100">
            <button data-id="{{$post->id}}" class="like-btn text-sm text-gray-500 hover:text-black transition-colors">
                {{$post->likes_count}} 
                <i class="{{$post->is_liked ? "fa-solid" : "fa-regular"}} fa-heart"></i>
            </button>
            <span class="text-sm text-gray-500"><span id="comments-count">{{$post->comments_count}}</span> comments</span>
        </div>
    </article>

    {{-- comments --}}
    <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white">
        <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Comments</h2>
        <form id="comment-form">
            <div class="flex gap-3 mb-6">
                <input type="text" name="content" placeholder="Write a comment..." class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                <button type="submit" class="bg-black text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">Post</button>
            </div>
            <ul id="comments-container" class="space-y-3">
                @forelse ($post->comments as $comment)
                    <li class="comment flex flex-col gap-3 py-4 border-b border-gray-100 last:border-0">
                        <div class="flex items-center justify-between w-full">
                            <a href="{{route("user.profile", $comment->user->id)}}" class="flex items-center gap-3 group">
                                <img 
                                    src="{{ asset($comment->user->profile->img_url) }}" 
                                    class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-200 group-hover:ring-gray-400 transition-all"
                                    alt="{{ $comment->user->first_name }}">
                                <span class="text-sm font-semibold text-gray-900 group-hover:text-black transition-colors">{{ $comment->user->first_name }} {{ $comment->user->last_name }}</span>
                            </a>
                            @if ($comment->user_id === auth()->user()->id)
                                <button class="delete-comment text-xs text-gray-400 hover:text-red-500 transition-colors shrink-0" data-id="{{$comment->id}}"><i class="fa-solid fa-trash"></i></button> 
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed pl-12">{{ $comment->content }}</p>
                    </li>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No comments yet.</p>
                @endforelse
            </ul>
        </form>
    </div>
</main>
@endsection

@section("script")
    @vite([
        "resources/js/like.js",
        "resources/js/comment.js"
    ])
@endsection