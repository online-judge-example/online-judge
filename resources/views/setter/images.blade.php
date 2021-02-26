@extends('layouts.app')

@section('content')
    <!-- update profile picture model -->
    <div class="modal fade" id="upload_picture" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/setter/images/upload') }}" method="post" enctype="multipart/form-data">
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
    <div class="container">
        <div class="row border rounded">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar rounded-left">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 pt-3">
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
                <div class="row mb-3">
                    <div class="col d-flex">
                        <h4 class="d-inline">Image Gallery</h4>
                        <span class="fa fa-upload ml-auto c-pointer link" title="Upload" data-toggle="modal" data-target="#upload_picture"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="alert alert-success dis-none" role="alert" id="alart">
                            Copy to the Clipboard
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($images as $image)
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
                        <div class="bg-white rounded border shadow-sm"><img src="{{asset('storage/'.$image)}}" alt="" class="img-fluid card-img-top" style="height: 120px">
                            <div class="p-2 text-center">
                                <span class="text-center m-0 c-pointer link copyLink" data-link="{{url('storage/'.$image)}}" >Copy link</span>
                                <p class="small m-0 d-none">{{url('storage/'.$image)}}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

        $('.copyLink').click(function () {

            var text = $(this).attr('data-link');
            var input = '<input type="" class="h-inp">';
            $('body').append(input);
            $('.h-inp').val(text).select();
            document.execCommand('copy');
            $('.h-inp').remove();
            //console.log(text);
            $('#alart').fadeIn(1000);
            $('#alart').fadeOut(3000);
            //alert('Copy To The Clipboard');
        });

    </script>

@endsection
