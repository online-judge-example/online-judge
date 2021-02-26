@extends('layouts.app')

@section('style')
    <!-- DataTable Css -->
    <link rel="stylesheet" type="text/css" href="{{asset('datatables/datatables.min.css')}}"/>
@endsection
@section('content')

    <div class="container">
        <div class="row border rounded">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar rounded-left">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 pt-3 mb-5">
                <div class="w-100 d-flex mb-3">
                    <h4>My Problems</h4>
                </div>

                <div class="table-responsive overflow-hidden">
                    <table class="table text-left" id="problemList">
                        <thead>
                        <tr>
                            <th class="border-0">Username</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Join Date</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($user as $ur)
                            <tr>
                                <td><a href="{{url('profile/'.$ur->id)}}">{{$ur->username}}</a></td>
                                @if($ur->user_type == 1)<td>Setter</td>
                                @else <td>Practice</td> @endif

                                <td>{{\Carbon\Carbon::parse($ur->created_at)->format('d M Y')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row d-flex justify-content-center pt-5">
                    {{$user->links()}}
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
@endsection
