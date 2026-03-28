@extends('layout.base')
@section("main")
    <main>
        <form method="POST" action="/auth/login">
            @csrf
            <input type="email" name="email" placeholder="email">
            <input type="password" name="password" placeholder="password">

            <button type="submit">submit</button>
        </form>
    </main>
@endsection
