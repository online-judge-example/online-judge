@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{asset('codemirror/lib/codemirror.css')}}">
@endsection
@section('content')
    <div class="container">
        <style>
            .suggest_list:hover{
                background: #ccc;

            }
        </style>

        <div class="row border rounded">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar rounded-left">
                @include('layouts.left_nav')
            </nav>
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 pt-3">
                <div class="row">
                    <div class="col">
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                {{$error}}
                            </div>
                        @endforeach
                        @if(Session::get('error'))
                            <div class="alert alert-danger" role="alert">
                                {{Session::get('error')}}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <!-- Problem configuration -->
                    <div class="col mb-2">
                        <h3><i class="fa fa-fw fa-bug text-info"></i> Debug Problem</h3>
                        <form method="post">
                        @csrf
                        <div class="w-100 mt-3">
                            <input type="text" name="problem" class="form-control form-control-sm" placeholder="Problem Title" id="problem">

                            <div class="w-100 border border-top-0 rounded-bottom" id="problem_suggestion">
                            </div>
                        </div>

                        </form>

                        @if(isset($problem_title))
                            <h4 class="mt-3">Problem Title: {{$problem_title}}</h4>
                        @endif
                        <p class="mt-3 mb-1">Source</p>
                        <div class="border rounded" id="source">
                            <textarea class="w-100 border rounded" id="source_code" name="source">{!! "#include<bits/stdio.h>" !!}</textarea>
                        </div>


                        <div class="w-100 pt-3">
                            <div class="row">
                                <div class="col-2">Language</div>
                                <div class="col-8">
                                    <select name="language" class="w-100 form-control" id="language">
                                        <option value="c">C GCC 9.1.0</option>
                                        <option value="cpp">C++ GCC 5.3.0</option>
                                        <option value="cpp14">g++ 14 GCC 9.1.0 </option>
                                        <option value="cpp17">g++ 17 GCC 9.1.0</option>
                                        <option value="java">Java JDK 11.0.4</option>
                                        <option value="python2">Python 2.7.16</option>
                                        <option value="python3">Python 3.7.4</option>
                                    </select>
                                </div>
                                <div class="col-2 text-right">
                                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- input output -->
                <div class="w-100 mb-5 mt-5" id="execution_details">
                    <!--
                    <div class="w-100 border rounded p-2 mb-2 mt-2">
                        <div class="d-flex">
                            <h5 class="text-left d-inline m-0">Testset 1</h5>
                            <h5 class="testset ml-auto c-pointer d-inline link font-weight-bold m-0">&plus;</h5>
                        </div>
                        <div class="w-100 content dis-none mt-2">
                            <div class="form-row">
                                <div class="col">
                                    <label for="validationTextarea"><b>Input 1</b></label>
                                    <textarea class="form-control editor" required rows="5" id="" placeholder="" ></textarea>
                                </div>
                                <div class="col">
                                    <label for="validationTextarea"><b>Output 1</b></label>
                                    <textarea class="form-control editor" required rows="5" id="" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                </div>

            </div>
        </div>
    </div>


@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- JavaScript Bundle with Popper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

    <script src="{{asset('codemirror/lib/codemirror.js')}}"></script>
    <script src="{{asset('codemirror/mode/clike.js')}}"></script>

    <script>
        var cppEditor = CodeMirror.fromTextArea(document.getElementById("source_code"), {
            value: "#include bits/stdio.h \n",
            lineNumbers: true,
            matchBrackets: true,
            mode: "text/x-c++src",
        });


        jQuery(document).ready(function() {
             // problem suggestion
            $('#problem_suggestion').hide();
            $('#problem').keyup(function () {

                if($(this).val().length){

                    $.ajax({
                        type:'POST',
                        url: '{{route('problem.debug.suggestion')}}',
                        data:{
                            text: $(this).val(),
                            _token: $("input[type=hidden]").val()
                        } ,

                        success: function(result){
                            $('#problem_suggestion').show();
                            $('#problem_suggestion').html(JSON.parse(result).list);
                            //console.log(JSON.parse(result).list);
                        },

                        error: function (result) {
                            console.log(result);
                        }
                    });

                }else{
                    $('#problem_suggestion').hide();
                }

            });



            /// submit problem and get problem result
            $("#submit").click(function () {
                cppEditor.save();
                var problem = '{{$problem_id}}';
                var code = document.getElementsByTagName('textarea')[0].value;
                var language = $('#language').val();
                //console.table([problem,code,language]);
                $('#execution_details').empty();

                if(problem>0 && code.length>0){
                    $('#execution_details').html('<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>');
                    $.ajax({
                        type:'POST',
                        url: '{{route('problem.debug.submit')}}',
                        data:{
                            _token: $("input[type=hidden]").val(),
                            problem: problem,
                            code: code,
                            language: language
                        } ,

                        success: function(result){

                            $('#execution_details').empty();
                            //console.log(JSON.parse(result));
                            var cnt = 1;
                            result = JSON.parse(result);
                            //console.log(result);
                            for(var x in result){

                                var s = cnt+'&nbsp;&nbsp'+result[x].verdict+'&nbsp;&nbsp Time: '+result[x].time_take+ 's&nbsp;&nbsp Memory: '+result[x].memory_take+'kb ';

                                var str = '<div class="w-100 border rounded p-2 mb-2 mt-2">\n' +
                                    '                        <div class="d-flex">\n' +
                                    '                            <p class="text-left d-inline m-0">Testset '+s+'</p>\n' +
                                    '                        </div>\n' +
                                    '                    </div>';
                                // append in page...
                                cnt++;
                                $('#execution_details').append(str);
                            }

                        },

                        error: function (result) {
                            console.log(result);
                        }
                    });
                }else{
                    $('#execution_details').html('<p class="text-danger">Please select the problem</p>');
                }
            });

        });



    </script>
@endsection
