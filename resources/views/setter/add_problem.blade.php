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
                <div class="w-100 d-flex">
                    <div class="btn-group ml-auto" role="group" aria-label="Basic example">
                        <a href="https://editor.codecogs.com/" type="button" class="border btn btn-light" target="_blank">Equation</a>
                        <a type="button" class="border btn btn-light" id="full_pre_btn">Full Preview</a>
                    </div>
                </div>
                <div class="w-100 dis-none" id="full_pre_container">
                    <div class="row">
                        <div class="col text-center">
                            <h3 class="title_con"></h3>
                            <h6 class="m-0">Time limit: 1 Second</h6>
                            <h6 class="m-0">Memory limit: 1024 KB</h6>
                            <h6 class="m-0">Standard input output</h6>
                        </div>
                    </div>
                    <div class="row pt-5">
                        <div class="col description_con">
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col">
                            <h5>Input</h5>
                            <div class="input_con"></div>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col">
                            <h5>Output</h5>
                            <div class="output_con"></div>
                        </div>
                    </div>

                    <div class="row pt-5">
                        <div class="col-12 col-sm-6">
                            <h5>Sample Input</h5>
                            <div class="sample_input_con"></div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h5>Sample Output</h5>
                            <div class="sample_output_con"></div>
                        </div>
                    </div>
                    <div class="row pt-5 pb-3">
                        <div class="col">
                            <h5>Note</h5>
                            <div class="note_con"></div>
                        </div>
                    </div>
                </div>

                <form method="post" action="{{$action}}" id="form_container">
                    @csrf
                    <input type="hidden" name="problem_id" @if(isset($problem)) value="{{$problem->id}}"@endif>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Problem</label>
                        <input name="title" type="text"  @if(isset($problem)) value="{{$problem->title}}" @else value="{{old('title')}}"@endif class="form-control title" id="exampleFormControlInput1" placeholder="Problem Title">
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
                                <textarea class="border-0 w-100 p-2" id="description_text" placeholder="" name="description">@if(isset($problem)){{$problem->description}}@endif{{old('description')}}</textarea>
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
                                <textarea class="border-0 w-100 p-2" id="input_text" name="input_format">@if(isset($problem)){{$problem->input_format}}@endif{{old('input_format')}}</textarea>
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
                                <textarea class="border-0 w-100 p-2" id="output_text" name="output_format">@if(isset($problem)){{$problem->output_format}}@endif{{old('output_format')}}</textarea>
                                <div id="output_display" class="d-none w-100 p-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <label for="validationTextarea"><b>Sample Input</b></label>
                            <textarea class="form-control editor" required rows="5" id="input" name="sample_input">@if(isset($problem)){{$problem->sample_input}}@endif{{old('sample_input')}}</textarea>
                        </div>
                        <div class="col">
                            <label for="validationTextarea"><b>Sample Output</b></label>
                            <textarea class="form-control editor" required rows="5" id="output" name="sample_output">@if(isset($problem)){{$problem->sample_output}}@endif{{old('sample_output')}}</textarea>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="w-100 border rounded">
                            <div class="w-100 border-bottom p-1 pl-3 d-flex">
                                <h6 class="m-0 mt-2 d-inline align-middle"><b>Note</b></h6>
                                <div class="btn-group ml-auto" role="group" aria-label="Basic example">
                                    <button type="button" class="border btn btn-active" id="note_edit"><span class="fa fa-edit"></span></button>
                                    <button type="button" class="border btn" id="note_pre"><span class="fa fa-eye"></span></button>
                                </div>
                            </div>
                            <div class="w-100">
                                <textarea class="border-0 w-100 p-2" id="note_text" placeholder="" name="note">@if(isset($problem)){{$problem->note}}@endif{{old('note')}}</textarea>
                                <div id="note_display" class="d-none w-100 p-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right my-4">
                        <button type="submit" class="btn btn-primary">Save</button>
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
