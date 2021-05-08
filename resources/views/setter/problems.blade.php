@extends('layouts.app')

@section('style')
    <!-- DataTable Css -->
    <link rel="stylesheet" type="text/css" href="{{asset('datatables/datatables.min.css')}}"/>
@endsection
@section('content')

    <div class="container">
        <div class="row border rounded">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar rounded-left">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 pt-3 mb-5">
                <div class="w-100 d-flex mb-3">
                    <h4><i class="fa fa-fw fa-superscript text-info"></i> My Problems</h4>
                </div>

                <div class="table-responsive overflow-hidden">
                    <table class="table text-left" id="problemList">
                        <thead>
                        <tr>
                            <th class="border-0">Title</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Solve/Tried</th>
                            <th class="border-0">Edit</th>
                        </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('script')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- DataTable plugin for jquery-->
    <script type="text/javascript" src="{{asset('datatables/datatables.min.js')}}" defer></script>

    <script type="text/javascript">
        // send a ajax request to get all the submission
        jQuery(document).ready(function() {

            // search with DataTable(jQuery plugin)
            $('#problemList').DataTable({
                "processing" : true,
                "serverSide" : true,
                "scrollX": false,
                "ajax" : {
                    "url" : "{{url('setter/search_problem')}}",
                    "dataType" : "json",
                    "type" : "POST",
                    "data" : {_token: "{{csrf_token()}}"}
                },

                "columns" : [
                    {"data" : "title"},
                    {"data" : "status"},
                    {"data" : "solve/tried"},
                    {"data" : "edit"},
                ]
            })

        });


    </script>
@endsection
