@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table text-left">
                        <thead>
                        <tr>
                            <th class="border-0">Submission ID</th>
                            <th class="border-0">Submission Date and Time</th>
                            <th class="border-0">Problem</th>
                            <th class="border-0">Language</th>
                            <th class="border-0">CPU</th>
                            <th class="border-0">Memory</th>
                            <th class="border-0">Verdict</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($submissions as $submission)
                            <tr>
                                <td><a href="{{url('submission/'.$submission->sub_id)}}">{{$submission->sub_id}}</a></td>
                                <td> {{\Carbon\Carbon::parse($submission->created_at)->format('d M Y')}} / {{\Carbon\Carbon::parse($submission->created_at)->format('h:m:s')}}</td>
                                <td><a href="{{url('problem/'.$submission->id)}}" class="text-decoration-none">{{$submission->title}}</a></td>
                                <td>{{config('app.language', 'Not Judged Yet')[$submission->language_id]}}</td>
                                <td>{{$submission->cpu}}</td>
                                <td>{{$submission->memory}}</td>
                                <td>{!! config('app.verdict', 'Not Judged Yet')[$submission->verdict] !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col d-flex justify-content-center pt-5">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
@endsection
