@extends("layout.base")
@section("main")
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-semibold text-black mb-6">Feed</h1>

        {{-- Search Bar --}}
        <div class="mb-6">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    id="search-input"
                    placeholder="Search posts and users..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-black transition-colors bg-white"
                    autocomplete="off"
                >
            </div>
        </div>

        {{-- Search Loading Skeleton --}}
        <div id="search-loading" class="hidden space-y-3 mb-6">
            <div class="animate-pulse space-y-3">
                <div class="h-3 w-20 bg-gray-200 rounded"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg">
                        <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-3 w-24 bg-gray-200 rounded"></div>
                            <div class="h-2 w-16 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg">
                        <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-3 w-24 bg-gray-200 rounded"></div>
                            <div class="h-2 w-16 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                </div>
                <div class="h-3 w-16 bg-gray-200 rounded mt-4"></div>
                <div class="border border-gray-100 rounded-lg p-4 space-y-3">
                    <div class="h-4 w-3/4 bg-gray-200 rounded"></div>
                    <div class="h-3 w-full bg-gray-100 rounded"></div>
                    <div class="h-3 w-2/3 bg-gray-100 rounded"></div>
                </div>
                <div class="border border-gray-100 rounded-lg p-4 space-y-3">
                    <div class="h-4 w-1/2 bg-gray-200 rounded"></div>
                    <div class="h-3 w-full bg-gray-100 rounded"></div>
                    <div class="h-3 w-1/3 bg-gray-100 rounded"></div>
                </div>
            </div>
        </div>

        {{-- Search Results Container --}}
        <div id="search-results" class="hidden mb-6"></div>

        {{-- Create Post Form --}}
        <div id="posts-feed">
            <div class="border border-gray-200 rounded-lg p-4 sm:p-6 mb-8 bg-white">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">New Post</h2>
                <form action="{{route("post.create")}}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
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
                    <div>
                        <label for="image-input" class="block text-sm font-medium text-gray-700 mb-1">Images <span class="text-gray-400 font-normal">(optional, max 5)</span></label>
                        <input type="file" id="image-input" name="images[]" multiple accept="image/jpg,image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-white file:text-gray-700 hover:file:bg-gray-50 file:cursor-pointer file:transition-colors">
                        <div id="image-preview" class="flex flex-wrap gap-3 mt-3"></div>
                    </div>
                    <div class="flex justify-end">
                        <input type="submit" value="Publish" class="bg-black text-white text-sm font-medium px-6 py-2 rounded-md hover:bg-gray-800 transition-colors cursor-pointer">
                    </div>
                </form>
            </div>
            {{-- Posts Feed --}}
            <div class="space-y-4">
                @forelse ($posts as $post)
                    <article class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white relative">
                        @if ($post->expire_at && $post->expire_at->isPast())
                            <span class="absolute top-3 right-3 px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500 border border-gray-200">Expired</span>
                        @else
                            @if ($post->user_id !== auth()->user()->id)
                                @if($post->is_joined) 
                                    <a href="{{route("groups.show", $post->group_id)}}" class="absolute top-3 right-3 px-3 py-1.5 text-xs font-medium rounded-md bg-black text-white hover:bg-gray-800 transition-colors inline-flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i> Go to group
                                    </a>
                                @else
                                    <form action="{{route("post.toggle_request", $post->id)}}" method="POST">
                                        @csrf
                                        <button class="request-btn absolute top-3 right-3 px-3 py-1.5 text-xs font-medium rounded-md {{ $post->is_requested ? 'border border-gray-300 text-gray-600 hover:text-black hover:border-black' : 'bg-black text-white hover:bg-gray-800' }} transition-colors">
                                            {{ $post->is_requested ? "Cancel" : "Request"}}
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endif
                        
                        <h3 class="text-lg font-semibold text-black mb-1">{{$post->title}}</h3>
                        <p class="text-xs text-gray-400 mb-3">{{$post->address}}</p>
                        <p class="text-sm text-gray-700 leading-relaxed mb-4">{{$post->content}}</p>

                        {{-- Post Images Gallery --}}
                        @if ($post->images && $post->images->count() > 0)
                            @php $count = $post->images->count(); @endphp
                            <div class="mb-4 grid gap-1 rounded-lg overflow-hidden border border-gray-100 {{ 
                                $count == 1 ? 'grid-cols-1' : 
                                ($count == 2 || $count == 4 ? 'grid-cols-2' : 
                                ($count == 3 ? 'grid-cols-2' : 'grid-cols-6')) 
                            }}">
                                @foreach ($post->images as $index => $image)
                                    @if ($index < 5)
                                        <div class="relative {{ 
                                            $count == 3 && $index == 0 ? 'col-span-2' : 
                                            ($count >= 5 && $index < 2 ? 'col-span-3' : 
                                            ($count >= 5 && $index >= 2 ? 'col-span-2' : '')) 
                                        }}">
                                            <img src="{{ asset($image->img_url) }}" 
                                                 class="w-full h-full object-cover hover:opacity-90 transition-opacity cursor-pointer {{ 
                                                    $count == 1 ? 'max-h-96' : 
                                                    ($count == 2 || $count == 4 ? 'aspect-square sm:aspect-video' : 
                                                    ($count == 3 && $index == 0 ? 'aspect-video' : 
                                                    ($count == 3 ? 'aspect-square' : 
                                                    ($count >= 5 && $index < 2 ? 'aspect-square sm:aspect-video' : 'aspect-square')))) 
                                                 }}" 
                                                 alt="Post image">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

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
        </div>
    </main>
@endsection

@section("script")
    @vite([
        "resources/js/like.js",
        "resources/js/search.js",
        "resources/js/image-preview.js"
    ])
@endsection