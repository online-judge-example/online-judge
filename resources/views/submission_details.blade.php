@extends('layouts.app')
@section('style')
<link href="{{ asset('css/prism.css')}}" rel="stylesheet">
@endsection
@section('content')
    @if(!empty($details))
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-bordered text-center m-0 rounded">
                        <thead>
                        <tr>
                            <th class="p-0">Submission ID</th>
                            <th class="p-0">User</th>
                            <th class="p-0">Problem</th>
                            <th class="p-0">Language</th>
                            <th class="p-0">CPU</th>
                            <th class="p-0">Memory</th>
                            <th class="p-0">Verdict</th>
                            <th class="p-0">Submission Time</th>
                            <th class="p-0">Judge Time</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td class="p-0">{{$details->sub_id}}</td>
                                <td class="p-0"><a href="{{url('profile/'.auth()->user()->username)}}">{{$details->username}}</a></td>
                                <td class="p-0"><a href="{{url('problem/'.$details->id)}}">{{$details->id}}</a></td>
                                <td class="p-0">{{config('app.language')[$details->language_id]}}</td>
                                <td class="p-0">{{$details->cpu}}</td>
                                <td class="p-0">{{$details->memory}}</td>
                                <td class="p-0">{!! config('app.verdict', 'Not Judge Yet')[$details->verdict] !!}</td>
                                <td class="p-0">{{\Carbon\Carbon::parse($details->created_at)->format('d M Y')}} / {{\Carbon\Carbon::parse($details->created_at)->format('h:m:s')}}</td>
                                <td class="p-0">{{\Carbon\Carbon::parse($details->created_at)->format('d M Y')}} / {{\Carbon\Carbon::parse($details->created_at)->format('h:m:s')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <h4>Code</h4>
                <pre class="border rounded p-3 d-flex">
                    <code class="language-clike">{{trim($details->code)}}</code>
                </pre>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/prism.js')}}"></script>
@endsection

