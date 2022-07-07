@extends('index')
@section('content')

    <style>input { margin: 10px; }</style>

    <form action="/api/login" method="post" class="d-flex flex-column">
        <input type="text" name="email">
        <input type="password" name="password">

        <input type="submit">
    </form>
@endsection
