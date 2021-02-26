@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row border rounded">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar rounded-left">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 pt-3 mb-5">
                <div class="w-100 d-flex mb-3">
                    <h4>Home: {{ucwords(auth()->user()->name)}}</h4>
                </div>
                <div class="w-100 border rounded p-3">

                </div>

            </div>
        </div>
    </div>

@endsection
@section('script')

@endsection

