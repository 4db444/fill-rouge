@extends("layout.base")
@section("main")
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-semibold text-black mb-6">Dashboard</h1>

        {{-- Create Post Form --}}
        <div class="border border-gray-200 rounded-lg p-4 sm:p-6 mb-8 bg-white">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">New Post</h2>
            <form action="{{route("post.create")}}" method="POST" class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" id="title" name="title" placeholder="Post title" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                </div>
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <textarea name="content" id="content" rows="4" placeholder="What's on your mind?" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors resize-vertical"></textarea>
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" id="address" name="address" placeholder="City or location" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                </div>
                <div class="flex justify-end">
                    <input type="submit" value="Publish" class="bg-black text-white text-sm font-medium px-6 py-2 rounded-md hover:bg-gray-800 transition-colors cursor-pointer">
                </div>
            </form>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 border border-gray-300 rounded-lg p-4 bg-gray-50">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $err)
                        <li class="text-sm text-gray-700">• {{$err}}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Posts Feed --}}
        <div class="space-y-4">
            @forelse ($posts as $post)
                <article class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white">
                    <h3 class="text-lg font-semibold text-black mb-1">{{$post->title}}</h3>
                    <p class="text-xs text-gray-400 mb-3">{{$post->address}}</p>
                    <p class="text-sm text-gray-700 leading-relaxed mb-4">{{$post->content}}</p>
                    <div class="flex items-center gap-4 pt-3 border-t border-gray-100">

                        
                        <button data-id="{{$post->id}}" class="like-btn text-sm text-gray-500 hover:text-black transition-colors">
                            {{$post->likes_count}} 
                            <i class="{{$post->is_liked ? "fa-solid" : "fa-regular"}} fa-heart"></i>
                        </button>

                        <a href="{{route("post.show", $post->id)}}" class="text-sm text-gray-500 hover:text-black transition-colors">{{$post->comments_count}} comments</a>
                    </div>
                </article>
            @empty
                <p class="text-sm text-gray-400 text-center py-12">No posts yet.</p>
            @endforelse
        </div>
    </main>
@endsection

@section("script")
    @vite(["resources/js/like.js"])
@endsection