@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if(Session::get('error'))
                <div class="alert alert-danger" role="alert">
                    {{Session::get('error')}}
                </div>
                @endif
                @if(Session::get('success'))
                <div class="alert alert-danger" role="alert">
                    {{Session::get('success')}}
                </div>
                @endif

                @error('file')
                <div class="alert alert-danger" role="alert">
                    {{$message}}
                </div>
                @enderror

                @error('language')
                <div class="alert alert-danger" role="alert">
                    {{$message}}
                </div>
                @enderror
                <!--
                @foreach($errors->all() as $error)
                    {{$error}}
                @endforeach
                -->
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-9 order-2 order-sm-2 order-md-1">
                <div class="row">
                    <div class="col text-center">
                        <h3 class="m-0">{{ucwords($problem->title)}}</h3>
                        <h6 class="m-0">Time limit: {{$problem->time_limit}} Second</h6>
                        <h6 class="m-0">Memory limit: {{$problem->memory_limit}} KB</h6>
                        <h6 class="m-0">Standard input output</h6>
                    </div>
                </div>
                <div class="row pt-5">
                    <div class="col">
                        {!! $problem->description !!}
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col">
                        <h5>Input</h5>
                        {!! $problem->input_format !!}
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col">
                        <h5>Output</h5>
                        {!! $problem->output_format !!}
                    </div>
                </div>

                <div class="row pt-5">
                    <div class="col-12 col-sm-6">
                        <h5>Sample Input</h5>
                        {!! nl2br($problem->sample_input) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        <h5>Sample Output</h5>
                        {!! nl2br($problem->sample_output) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 order-1 order-sm-1 order-md-2 mb-5">
                <div class="row p-2 p-sm-0 mt-5">
                    <div class="col border rounded p-0">
                        <div class="border-bottom p-2">
                            <h5 class="m-0">Submit?</h5>
                        </div>
                        <div class="p-2 pb-0 text-center">
                            <form action="{{url('/submit')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <table class="table">
                                <tr>
                                    <td class="border-0 pl-0">Language:</td>
                                    <td class="border-0 pl-0 pr-0">

                                        <select name="language" class="w-100 form-control form-control-sm">
                                            <option value="c">C GCC 9.1.0</option>
                                            <option value="cpp">C++ GCC 5.3.0</option>
                                            <option value="cpp14">g++ 14 GCC 9.1.0 </option>
                                            <option value="cpp17">g++ 17 GCC 9.1.0</option>
                                            <option value="java">Java JDK 11.0.4</option>
                                            <option value="python2">Python 2.7.16</option>
                                            <option value="python3">Python 3.7.4</option>
                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 pl-0">Chose file</td>
                                    <td class="border-0 pl-0 pr-0">
                                        <input type="file" name="file" class="w-100 @error('file') text-danger @enderror">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="border-0 pl-0">
                                        <input type="submit" value="submit" class="text-center border rounded p-1 pl-2 pr-2">
                                    </td>
                                </tr>
                            </table>
                                <input type="hidden" name="problem_id" value="{{$problem_number}}">
                            </form>
                        </div>
                    </div>
                </div>
                @if(!$my_submission->isEmpty())
                <div class="row mt-5 d-none d-lg-block">
                    <div class="col border rounded p-0">
                        <div class="border-bottom p-2">
                            <h5 class="m-0">Last Submission</h5>
                        </div>
                        <div class="p-0 pl-2 pr-2 text-center">
                            <table class="table table-sm">
                                <tr>
                                    <th class="p-0">Submission</th>
                                    <th class="pt-0 pb-0">Time</th>
                                    <th class="p-0">Verdict</th>
                                </tr>
                                @foreach($my_submission as $item)
                                <tr>
                                    <td class="pl-0 pr-0 align-middle"><a href="{{url('submission/'.$item->sub_id)}}" target="_blank">{{$item->sub_id}}</a></td>
                                    <td class="align-middle">
                                        {{\Carbon\Carbon::parse($item->created_at)->format('d M Y')}}
                                        <br>
                                        {{\Carbon\Carbon::parse($item->created_at)->format('h:m:s')}}
                                    </td>
                                    <td class="pl-0 pr-0 align-middle">{!! config('app.verdict')[$item->verdict.'-s'] !!}</td>
                                @endforeach
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="row pt-5">
            <div class="col">
                <h5>Note</h5>
                {!! $problem->note !!}
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Setter: </p>
            </div>
        </div>
    </div>
@endsection
