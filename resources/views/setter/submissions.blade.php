@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row border rounded">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar rounded-left">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 pt-3 mb-5">
                <div class="w-100 d-flex mb-3">
                    <h4>Submissions</h4>
                </div>

                <div class="table-responsive overflow-hidden">
                    <table class="table text-left" id="problemList">
                        <thead>
                        <tr>
                            <th class="border-0">Sub ID</th>
                            <th class="border-0">Problem</th>
                            <th class="border-0">Username</th>
                            <th class="border-0">Date and Time</th>
                            <th class="border-0">Language</th>
                            <th class="border-0">Verdict</th>
                        </tr>
                        </thead>
                        <tbody id="table">
                        @foreach($submission as $sub)
                            <tr>
                                <td><a href="{{url('submission/'.$sub->sub_id)}}" target="_blank">{{$sub->sub_id}}</a></td>
                                <td><a href="{{url('problem/'.$sub->problem_id)}}" target="_blank">{{substr($sub->title, 0 ,15).'...'}}</a></td>
                                <td><a href="{{url('profile/'.$sub->username)}}" target="_blank">{{$sub->username}}</a></td>
                                <td>{{\Carbon\Carbon::parse($sub->created_at)->format('d M Y-h:m:s')}}</td>
                                <td>{!! config('app.language')[$sub->language_id] !!}</td>
                                <td>{!! config('app.verdict')[$sub->verdict] !!}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-center pt-5" id="link">
                        {{$submission->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

        (function update() {
            url = new URL(window.location.href);

            if (url.searchParams.get('page')<2) {
                $.ajax({
                    type: 'GET',
                    url: "{{url('setter/sub')}}",
                    dataType: 'json',

                    success: function (result) {
                        $('#table').html(result.table);
                        $('#link').html(result.links);
                        //console.log(result)
                    },
                    error: function (data) {
                        console.log(data);
                    }
                }).then(function() {           // on completion, restart
                    setTimeout(update, 30000);  // function refers to itself
                });
            }
        })();

    </script>
@endsection
