@extends('layouts.app')
@section('style')
    <style>
        textarea{
            resize: vertical;
        }
        textarea:focus-visible{
            outline: 0px;
        }
        .btn-active{
            background-color: #e2e6ea;
            border-color: #dae0e5;
        }
        .btn:focus{
            box-shadow: none;
        }
    </style>

@endsection
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <form action="" method="">
                       @csrf
                       <div class="custom-file">
                           <input type="file" class="custom-file-input" id="customFile">
                           <label class="custom-file-label" for="customFile">Choose file</label>
                       </div>
                   </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary border">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row border rounded">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar rounded-left">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 pt-3">
                <div class="w-100 d-flex">
                    <div class="btn-group ml-auto" role="group" aria-label="Basic example">
                        <a type="button" class="border btn btn-light" data-toggle="modal" data-target="#exampleModal">Image</a>
                        <a href="https://editor.codecogs.com/" type="button" class="border btn btn-light" target="_blank">Equation</a>
                        <a type="button" class="border btn btn-light" id="">Full Preview</a>
                    </div>
                </div>
                <form method="" action="">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Problem</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Problem Title">
                    </div>

                    <div class="form-group">
                        <div class="w-100 border rounded">
                            <div class="w-100 border-bottom p-1 pl-3 d-flex">
                                <h6 class="m-0 mt-2 d-inline align-middle"><b>Problem Description</b></h6>
                                <div class="btn-group ml-auto" role="group" aria-label="Basic example">
                                    <button type="button" class="border btn btn-active" id="des_edit"><span class="fa fa-edit"></span></button>
                                    <button type="button" class="border btn" id="des_pre"><span class="fa fa-eye"></span></button>
                                </div>
                            </div>
                            <div class="w-100">
                                <textarea class="border-0 w-100 p-2" id="description_text" placeholder="" ></textarea>
                                <div id="description_display" class="d-none w-100 p-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-100 border rounded">
                            <div class="w-100 border-bottom p-1 pl-3 d-flex">
                                <h6 class="m-0 mt-2 d-inline align-middle"><b>Input Format</b></h6>
                                <div class="btn-group ml-auto" role="group" aria-label="Basic example">
                                    <button type="button" class="border btn btn-active" id="input_edit"><span class="fa fa-edit"></span></button>
                                    <button type="button" class="border btn" id="input_pre"><span class="fa fa-eye"></span></button>
                                </div>
                            </div>
                            <div class="w-100">
                                <textarea class="border-0 w-100 p-2" id="input_text" placeholder="" ></textarea>
                                <div id="input_display" class="d-none w-100 p-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-100 border rounded">
                            <div class="w-100 border-bottom p-1 pl-3 d-flex">
                                <h6 class="m-0 mt-2 d-inline align-middle"><b>Output Format</b></h6>
                                <div class="btn-group ml-auto" role="group" aria-label="Basic example">
                                    <button type="button" class="border btn btn-active" id="output_edit"><span class="fa fa-edit"></span></button>
                                    <button type="button" class="border btn" id="output_pre"><span class="fa fa-eye"></span></button>
                                </div>
                            </div>
                            <div class="w-100">
                                <textarea class="border-0 w-100 p-2" id="output_text" placeholder="" ></textarea>
                                <div id="output_display" class="d-none w-100 p-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <label for="validationTextarea"><b>Sample Input</b></label>
                            <textarea class="form-control editor" required rows="5" id="" placeholder="" ></textarea>
                        </div>
                        <div class="col">
                            <label for="validationTextarea"><b>Sample Output</b></label>
                            <textarea class="form-control editor" required rows="5" id="" placeholder=""></textarea>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="w-100 border rounded">
                            <div class="w-100 border-bottom p-1 pl-3 d-flex">
                                <h6 class="m-0 mt-2 d-inline align-middle"><b>Note</b></h6>
                                <div class="btn-group ml-auto" role="group" aria-label="Basic example">
                                    <button type="button" class="border btn btn-active" id="output_edit"><span class="fa fa-edit"></span></button>
                                    <button type="button" class="border btn" id="output_pre"><span class="fa fa-eye"></span></button>
                                </div>
                            </div>
                            <div class="w-100">
                                <textarea class="border-0 w-100 p-2" id="output_text" placeholder="" ></textarea>
                                <div id="output_display" class="d-none w-100 p-2"></div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <script type="text/javascript">
        var request_url = "{{url('/parse_markdown')}}";
    </script>
    <script type="text/javascript" src="{{asset('js/markdown_display.js')}}"></script>
@endsection
