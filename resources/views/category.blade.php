@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 pl-2 pr-2">
                <div class="row">
                    @foreach($category as $item)
                        <div class="col-sm-6 col-md-4 col-lg-4 mt-3">
                            <div class="category_wrapper border p-4 pb-0 bg-custom-dark small-round">
                                <h4 class="text-center text-orange mt-2 mb-2">
                                    <a class="text-orange-hover" href="{{url('/practice/'.str_replace(" ", "_", $item->name))}}">{{ucwords($item->name)}}</a>
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
