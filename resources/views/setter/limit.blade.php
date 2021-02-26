@extends('layouts.app')

@section('content')
    <!-- Modal for category selection -->
    <div class="modal fade" id="category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-1 pr-2 pl-2">
                    <h5 class="modal-title" id="exampleModalLabel">Update Categories</h5>
                    <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <form method="post" action="{{url('/setter/problem/update/category')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="problem_id" value="{{$problem->id}}">
                    <div class="modal-body">
                        <div class="row">
                            @foreach($categories as $category)
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" name="category[]" type="checkbox" value="{{$category->id}}" id="defaultCheck1" @if(in_array($category->id, $category_id)) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        {{ucwords($category->name)}}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="modal-footer p-1">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal for update time limit -->
    <div class="modal fade" id="time_limit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Time Limit in second</h5>
                    <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <form method="post" action="{{url('/setter/problem/update/timelimit')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="problem_id" value="{{$problem->id}}">
                    <div class="modal-body">
                        <input type="text" name="time_limit" class="form-control" placeholder="1">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for update memory limit -->
    <div class="modal fade" id="memory_limit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Memory Limit in KiloByte (KB)</h5>
                    <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <form method="post" action="{{url('/setter/problem/update/memorylimit')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="problem_id" value="{{$problem->id}}">
                    <div class="modal-body">
                        <input type="text" name="memory_limit" class="form-control" placeholder="1024">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for testcase -->
    <div class="modal fade" id="testcase" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Testcase</h5>
                    <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <form method="post" action="{{url('/setter/testcase/upload')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="problem_id" value="{{$problem->id}}">
                    <div class="modal-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="border-0">Input: </td>
                                    <td class="border-0"><input type="file" name="input"></td>
                                </tr>
                                <tr>
                                    <td class="border-0">Output: </td>
                                    <td class="border-0"><input type="file" name="output"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="container">
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
                        <div class="border rounded">
                            <table class="table m-0">
                                <thead>
                                <tr class="text-white bg-dark">
                                    <th scope="col" class="text-left p-1 pl-3 border-0">Problem</th>
                                    <th scope="col" class="text-right p-1 pr-3 border-0"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td scope="row" class="text-left p-1 pl-3">Title: {{$problem->title}}</td>
                                    <td class="text-right p-1 pr-3"><a href="{{url('/setter/problem/update/'.$problem->id)}}"><i class="fa fa-edit"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row" class="text-left p-1 pl-3">Time Limit: {{$problem->time_limit.'s'}}</td>
                                    <td class="text-right p-1 pr-3"><i class="fa fa-edit link c-pointer" data-bs-toggle="modal" data-bs-target="#time_limit"></i></td>
                                </tr>
                                <tr>
                                    <td scope="row" class="text-left p-1 pl-3">Memory Limit: {{$problem->memory_limit.' KB'}}</td>
                                    <td class="text-right p-1 pr-3"><i class="fa fa-edit link c-pointer" data-bs-toggle="modal" data-bs-target="#memory_limit"></i></td>
                                </tr>
                                <tr>
                                @if($problem->status == 1)
                                    <td scope="row" class="text-left p-1 pl-3 status-text">Status: Visible</td>
                                    <td class="text-right p-1 pr-3"><i class="fa fa-eye c-pointer link status" data-id="{{$problem->id}}" data-current="{{$problem->status}}"></i></td>
                                @else
                                    <td scope="row" class="text-left p-1 pl-3 status-text">Status: Not Visible</td>
                                    <td class="text-right p-1 pr-3"><i class="fa fa-eye-slash c-pointer link status" data-id="{{$problem->id}}" data-current="{{$problem->status}}"></i></td>
                                @endif
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- category configuration -->
                    <div class="col">
                        <div class="border rounded">
                            <table class="table m-0">
                                <thead>
                                <tr class="text-white bg-dark">
                                    <th scope="col" class="text-left p-1 pl-3 border-0">Category</th>
                                    <th scope="col" class="text-right p-1 pr-3 border-0"><i class="fa fa-edit c-pointer" data-bs-toggle="modal" data-bs-target="#category"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    @if(in_array($category->id, $category_id))
                                        <tr>
                                            <td scope="row" class="text-left p-1 pl-3">{{ucwords($category->name)}}</td>
                                            <td class="text-right p-1 pr-3">
                                                <i class="fa fa-trash c-pointer link category-delete" data-category-id="{{$category->id}}" data-problem-id="{{$problem->id}}"></i>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <h5>Testset: </h5>
                    </div>
                    <div class="col text-right">
                        <i class="fa fa-upload pr-1 c-pointer link" data-bs-toggle="modal" data-bs-target="#testcase"></i>
                    </div>
                </div>
               <div class="row mb-5">
                   <?php $case_no = 1; ?>
                   @foreach($testcase as $tc)
                   <div class="col-sm-12 col-md-6  mb-2">
                       <div class="w-100 border rounded d-flex p-1 pl-2 pr-2">
                           <p class="m-0">Testcase {{$case_no}}</p>
                           <div class="ml-auto">
                               <a href="{{url('/setter/testcase/download/'.$tc->id.'/'.$case_no++.'/'.$problem->id)}}"><i class="fa fa-download"></i></a>
                               <i class="fa fa-trash ml-2 c-pointer link tc-delete" data-case-id="{{$tc->id}}" data-problem-id="{{$problem->id}}"></i>
                           </div>
                       </div>
                   </div>
                   @endforeach
               </div>
                <!-- input output -->
                <div class="w-100 mb-5 mt-5 d-none">
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

                    <div class="w-100 border rounded p-2 mb-2">
                        <div class="d-flex">
                            <h5 class="text-left d-inline m-0">Testset 2</h5>
                            <h5 class="testset ml-auto c-pointer d-inline link font-weight-bold m-0">&plus;</h5>
                        </div>
                        <div class="w-100 content dis-none">
                            <div class="form-row">
                                <div class="col">
                                    <label for="validationTextarea"><b>Input 2</b></label>
                                    <textarea class="form-control editor" required rows="5" id="" placeholder="" ></textarea>
                                </div>
                                <div class="col">
                                    <label for="validationTextarea"><b>Output 2</b></label>
                                    <textarea class="form-control editor" required rows="5" id="" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

    <script>
       jQuery(document).ready(function(){

           //$('.content').hide();
           $('.testset').click(function () {
               if($(this).parent().next().is(':hidden')){
                   $(this).parent().next().slideDown('slow');
                   $(this).html('&minus;');
               }else{
                   $(this).parent().next().slideUp('slow');
                   $(this).html('&plus;');
               }

               //console.log($(this).next());
           })

           /// update problem status

           $('.status').on('click', function () {
               //console.log('hello');
               let span = $(this);
               // data-id is the id of the problem
               // data-current is the current status of the problem
               $.ajax({
                   type: 'GET',
                   url: "{{url('setter/problem/update_status')}}",
                   dataType: 'json',
                   data: {
                       id: $(this).attr('data-id'),
                       status: $(this).attr('data-current')
                   },
                   success: function (result) {

                       if(result.success == "true"){
                           // change the icon
                           span.toggleClass('fa-eye')   // if icon is eye then remove, if not eye then add
                           span.toggleClass('fa-eye-slash') // if icon is eye-slash then remove, if not eye then add
                           span.attr('data-current', result.current)   // change the current status
                           if(result.current == 1){
                                $('.status-text').text("Status: Visible");
                           }else{
                               $('.status-text').text("Status: Not Visible");
                           }
                           //console.log(span)
                       }
                   },
                   error: function (data) {
                       //console.log(data);
                   }
               })
           });

           /// delete single category
           $('.category-delete').on('click', function () {
               //console.log('hello');
               let span = $(this);

               $.ajax({
                   type: 'GET',
                   url: "{{url('setter/problem/delete_category')}}",
                   dataType: 'json',
                   data: {
                       category_id: $(this).attr('data-category-id'),
                       problem_id: $(this).attr('data-problem-id'),
                   },
                   success: function (result) {
                        //console.log('success');
                       if(result.success == "true"){
                           // change the icon
                           span.parent().parent().remove();
                           console.log(span)
                       }
                   },
                   error: function (data) {
                       //console.log(data);
                   }
               })
           });

           // delete testcase
           $('.tc-delete').click(function () {
               let span = $(this);
               $.ajax({
                   type: 'GET',
                   url: "{{url('setter/testcase/delete')}}",
                   dataType: 'json',
                   data: {
                       testcase_id: $(this).attr('data-case-id'),
                       problem_id: $(this).attr('data-problem-id'),
                   },
                   success: function (result) {
                       console.log('success');
                       if(result.success == "true"){
                           // change the icon
                           span.parent().parent().parent().remove();
                           //console.log(span)
                       }
                   },
                   error: function (data) {
                       //console.log(data);
                   }
               })
           });


       });

   </script>
@endsection
