@extends("layout.base")
@section("main")
    <main>
        <h1>dashboard</h1>
        <form action="{{route("post.create")}}" method="POST">
            <div>
                title : <input type="text" name="title">
            </div>
            <div>
                content : <textarea name="content" id="content" cols="30" rows="10"></textarea>
            </div>
            <div>
                address : <input type="text" name="address">
            </div>
            <input type="submit" value="submit">
        </form>
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{$err}}</li>
                @endforeach
            </ul>
        @endif
        @forelse ($posts as $post)
            <div style="border: 1px solid black">
                <h5>{{$post->title}}</h5>
                <p>city: {{$post->address}}</p>
                <p>{{$post->content}}</p>

                    
                <button data-id="{{$post->id}}" class="like-btn" onclick="handleLike(event)">{{$post->likes_count}} {{$post->is_liked ? "unlike" : "like"}}</button>
            </div>
        @empty
            <p>no posts</p>
        @endforelse
    </main>
@endsection

@section("script")
    <script>
        const likeButtons = document.querySelectorAll(".like-btn");

        async function handleLike (event) {

            const postId = event.target.dataset.id;
            const button = event.target;

            event.preventDefault();
            const res = await fetch(`http://localhost:8000/posts/${postId}/toggle_like`, {
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",
                    "Accept" : "application/json"
                }
            });
            const {is_liked, likes} = await res.json();
            
            button.innerHTML = `${likes} ${is_liked ? "unlike" : "like"}`
        }
    </script>
@endsection