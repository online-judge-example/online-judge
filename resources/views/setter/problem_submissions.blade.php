@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="w-100 mb-3">
                    <h4>Problem: <a href="{{url('problem/'.$problem->id)}}">{{$problem->title}}</a></h4>
                </div>
                @if(!$submissions->isEmpty())
                <div class="table-responsive">
                    <table class="table text-left">
                        <thead>
                        <tr>
                            <th class="border-0">Submission ID</th>
                            <th class="border-0">Username</th>
                            <th class="border-0">Submission Date and Time</th>
                            <th class="border-0">Language</th>
                            <th class="border-0">Verdict</th>
                            <!--
                            <th class="border-0">Rejudge</th>
                            -->
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($submissions as $submission)
                            <tr>
                                <td><a href="{{url('submission/'.$submission->sub_id)}}">{{$submission->sub_id}}</a></td>
                                <td><a href="{{url('profile/'.$submission->username)}}">{{$submission->username}}</a></td>
                                <td> {{\Carbon\Carbon::parse($submission->created_at)->format('d M Y')}} / {{\Carbon\Carbon::parse($submission->created_at)->format('h:m:s')}}</td>
                                <td>{{config('app.language', 'Not Judged Yet')[$submission->language_id]}}</td>
                                <td>{!! config('app.verdict', 'Not Judged Yet')[$submission->verdict] !!}</td>
                                <!--
                                <td><i class="fa fa-repeat"></i></td>
                                -->
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        @if(!$submissions->isEmpty())
        <div class="row">
            <div class="col d-flex justify-content-center pt-5">
                {{ $submissions->links() }}
            </div>
        </div>
        @endif
    </div>
@endsection
