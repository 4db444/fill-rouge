@extends("layout.base")

@section("main")
    <h1>profile page</h1>
    @if ($user->id === auth()->user()->id)
        <form action="{{route("user.info.update", $user->id)}}" method="POST">
            @method("PUT")
            <div>
                first name: <input type="text" name="first_name" value="{{$user->first_name}}">
            </div>

            <div>
                last name: <input type="text" name="last_name" value="{{$user->last_name}}">
            </div>

            <div>
                bio: 
                <textarea name="bio" id="bio" cols="30" rows="10">{{$user->bio}}</textarea>
            </div>
            <input type="submit" value="save">
        </form>

        <form action="{{route("user.password.update", $user->id)}}" method="POST">
            @method("put")
            <div>
                old password: <input type="password" name="old_password">
            </div>

            <div>
                new password: <input type="password" name="new_password">
            </div>

            <div>
                password confirmation: <input type="password" name="new_password_confirmation">
            </div>
            <button type="submit">save</button>
        </form>

        <ul>
            @if ($errors->any())
                @foreach ($errors->all() as $err)
                    <li>{{$err}}</li>
                @endforeach
                
            @endif
        </ul>
    
    @endif
@endsection