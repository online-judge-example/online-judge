@extends('layouts.app')

@section('content')
    <!-- update profile picture model -->
    @if(auth()->user()->id == $user->id)
    <div class="modal fade" id="upload_profile_pic" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/profile/upload') }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="picture">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary border">Upload</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal for update country -->
    <div class="modal fade" id="update_country" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Country</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/profile/country') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="custom-file">
                            <input type="text" class="form-control"  name="country" placeholder="Country" value="{{ $user->country }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary border">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  Modal for institution -->
    <div class="modal fade" id="update_institution" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Institution</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/profile/institution') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="custom-file">
                            <input type="text" class="form-control" id="" name="institution" placeholder="Institution" value="{{ $user->institution }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary border">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endif
    <div class="container pt-5">
        <div class="row">
            @if(Session::has('success'))
                <div class="w-100">
                    <div class="alert alert-success" role="alert">
                        {{Session::get('success')}}
                    </div>
                </div>
            @endif
            @foreach($errors->all() as $error)
                <div class="w-100">
                    <div class="alert alert-danger" role="alert">
                        {{$error}}
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row pt-5">
            <div class="col border rounded px-md-5 pb-5 text-center" style="background-color:#f0f0f0;">
                <div class="overflow-hidden text-center border d-inline-block rounded-circle" style="position: relative; margin-top: -70px;" onmouseover="show_upload()" onmouseleave="hide_upload()">
                    <img src="{{asset('storage/profile/'.$user->picture)}}" alt="pfofile pic" class="rounded-circle" style="width: 150px;">
                    @if(auth()->user()->id == $user->id)
                    <div class="position-absolute w-100 bg-custom-dark pb-3 pt-1 dis-none" style="bottom: 0px;" id="upload_container">
                        <span class="text-center text-decoration-none link c-pointer" data-toggle="modal" data-target="#upload_profile_pic">Upload Image</span>
                    </div>
                    @endif
                </div>
                <h4 class="text-center mt-3">{{ $user->name }}</h4>
                <p class="text-center mb-4">{{ '@'.$user->username }}</p>
                <div class="row border-bottom border-top py-5">
                    <div class="col text-right">
                        <p class="m-0">Rank</p>
                        <p class="m-0">Country</p>
                        <p class="m-0">Institution</p>
                        <p class="m-0">Email</p>
                    </div>
                    <div class="col text-left">
                        <p class="m-0"><img src="{{asset('images/crown.svg')}}" style="width: 15px"> {{ $solve_count*20 }}</p>
                        <p class="m-0"><i class="fa fa-home"></i> {{ $user->country }} <i class="fa fa-edit link c-pointer" data-toggle="modal" data-target="#update_country"></i></p>
                        <p class="m-0"><i class="fa fa-university"></i> {{ $user->institution }} <i class="fa fa-edit link c-pointer" data-toggle="modal" data-target="#update_institution"></i></p>
                        <p class="m-0"><i class="fa fa-envelope"></i> {{ $user->email }}</p>
                    </div>
                </div>
                <div class="row p-3">
                    <div class="col border-right p-3">
                        <p class="m-0 text-center">Solve</p>
                        <h4 class="m-0 text-center">{{ $solve_count }}</h4>
                    </div>
                    <div class="col border-right p-3">
                        <p class="m-0 text-center">Submissions</p>
                        <h4 class="m-0 text-center">{{ $sub_count }}</h4>
                    </div>
                    <div class="col p-3">
                        <p class="m-0 text-center">Score</p>
                        <h4 class="m-0 text-center">{{ round($solve_count/$sub_count,3) }}</h4>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col text-center">
                        <h3 class="mb-4">List of Solve Problem</h3>
                        @foreach($submissions as $sub)
                        <div class="border d-inline-block py-1 px-2 rounded border-dark mb-1">{{ $sub->problem_id }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script type="text/javascript">
        var profile_btn = document.getElementById('upload_container');
        function show_upload() {
            profile_btn.style.display = "block";
        }
        function hide_upload() {
            profile_btn.style.display = "none";
        }
    </script>
@endsection
