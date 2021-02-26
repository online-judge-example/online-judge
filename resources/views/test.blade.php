@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <p>Content goes here</p>
                <form method="POST" action="{{url('setter/search_problem')}}">
                    @csrf
                    <input type="text" name="search" value="">
                    <input type="text" name="length" value="10">
                    <input type="text" name="start" value="0">
                    <input type="text" name="search" value="">
                </form>
            </div>
        </div>
    </div>


@endsection
