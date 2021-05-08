@extends('layouts.app')

@section('style')
    <!-- DataTable Css -->
@endsection
@section('content')

    <div class="container">
        <div class="row border rounded">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar rounded-left">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 pt-3 mb-5">
                <form action="{{route('admin.home')}}" method="get">
                    <div class="w-100 d-flex mb-3">
                        <h4 class="d-inline">User List</h4>
                        <h4 class="d-inline ml-auto"><input class="form-control form-control-sm" type="text" name="search" placeholder="Search"></h4>
                    </div>
                </form>
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
                        @foreach($users as $user)
                            <tr>
                                <td><a href="{{url('profile/'.$user->username)}}">{{$user->username}}</a></td>
                                <td>
                                    @if($user->user_type == 1)Setter
                                    @else  <a href="{{route('admin.changeuser', ['id'=> $user->id])}}">Practice</a> @endif
                                </td>
                                <td>{{\Carbon\Carbon::parse($user->created_at)->format('d M Y')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row d-flex justify-content-center pt-5">
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
@endsection
