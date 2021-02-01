@extends('layouts.app')

@section('content')
    <div class="container">
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
                        <p>{{$problem->description}}</p>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col">
                        <h5>Input</h5>
                        <p>{{$problem->input_format}}</p>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col">
                        <h5>Output</h5>
                        <p>{{$problem->output_format}}</p>
                    </div>
                </div>

                <div class="row pt-5">
                    <div class="col-12 col-sm-6">
                        <h5>Sample Input</h5>
                        <p>{{$problem->sample_input}}</p>
                    </div>
                    <div class="col-12 col-sm-6">
                        <h5>Sample Output</h5>
                        <p>{{$problem->sample_output}}</p>
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
                            <form action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <table class="table">
                                <tr>
                                    <td class="border-0 pl-0">Language:</td>
                                    <td class="border-0 pl-0 pr-0">

                                        <select name="language" class="w-100">
                                            <option>C</option>
                                            <option>C++</option>
                                            <option>Java</option>
                                            <option>Python</option>
                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 pl-0">Chose file</td>
                                    <td class="border-0 pl-0 pr-0">
                                        <input type="file" name="submit" class="w-100">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="border-0 pl-0">
                                        <input type="submit" value="submit" class="text-center">
                                    </td>
                                </tr>
                            </table>
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
                                    <td class="pl-0 pr-0 align-middle"><a href="" target="_blank">{{$item->sub_id}}</a></td>
                                    <td class="align-middle">
                                        {{\Carbon\Carbon::parse($item->created_at)->format('d M Y')}}
                                        <br>
                                        {{\Carbon\Carbon::parse($item->created_at)->format('h:m:s')}}
                                    </td>
                                    <td class="pl-0 pr-0 align-middle">
                                        @if($item->verdict == 1){{'wrong answer'}}
                                            @elseif($item->verdict == 2){{'Time Limit'}}
                                            @elseif($item->verdict == 3){{'Accept'}}
                                            @endif
                                    </td>
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
                <p><b></b></p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Setter: </p>
            </div>
        </div>
    </div>
@endsection
