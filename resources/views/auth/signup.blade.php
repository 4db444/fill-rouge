@extends("layout.base")
@section("main")
    <main>
        <form action="/auth/signup" method="POST">
            <input type="text" name="first_name" placeholder="first_name">
            <input type="text" name="last_name" placeholder="last_name">
            <input type="email" name="email" placeholder="email">
            <input type="password" name="password" placeholder="password">
            <input type="password" name="password_confirmation" placeholder="password_confirmation">

            <button type="submit">submit</button>
        </form>
    </main>
@endsection