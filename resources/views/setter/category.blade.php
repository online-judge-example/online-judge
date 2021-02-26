@extends('layouts.app')

@section('content')
    <!-- Model for create category -->
    <div class="modal fade" id="category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/setter/category/create') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="text" name="category" class="form-control" placeholder="Category Name">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary border">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal for update category -->
    <div class="modal fade" id="update_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/setter/category/update') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="text" name="category" class="form-control col-12" placeholder="Category Name" id="category_name">
                        <input type="number" name="position" class="form-control col-12 mt-2" placeholder="Position" id="category_position">
                        <input type="hidden" name="category_id" class="form-control" id="category_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary border">Update</button>
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
            <div class="col-md-10 ml-sm-auto col-lg-10 px-4 py-3">
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
                <div class="row">
                    <div class="col">
                        @if(Session::get('error'))
                            <div class="alert alert-danger" role="alert">
                                {{Session::get('error')}}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="border rounded">
                            <table class="table m-0">
                                <thead>
                                <tr class="text-white bg-dark">
                                    <th scope="col" class="border-0">Category</th>
                                    <th scope="col" class="border-0">Position</th>
                                    <th scope="col" class="border-0">Visibility</th>
                                    <th scope="col" class="border-0">Last Modified</th>
                                    <th scope="col" class="border-0 text-right"><i class="fa fa-edit c-pointer ml-1" title="Edit" data-toggle="modal" data-target="#category"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td scope="row" class="">{{ucwords($category->name)}}</td>
                                        <td scope="row" class="">{{ucwords($category->position)}}</td>
                                        <td scope="row" class="">
                                            @if($category->visibility == 0) <a href="{{ url('/setter/category/visibility/'.$category->id.'/1') }}"><i class="fa fa-eye-slash" title="Not Visible"></i></a>
                                            @else <a href="{{ url('/setter/category/visibility/'.$category->id.'/0') }}"><i class="fa fa-eye" title="Visible"></i></a> @endif
                                        </td>
                                        <td scope="row" class="">{{ \Carbon\Carbon::parse($category->updated_at)->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <i class="fa fa-edit c-pointer link mr-1 edit_category" data-title="{{ $category->name }}" data-position="{{ $category->position }}" data-id="{{ $category->id }}" title="Edit"></i>
                                            <a href="{{ url('/setter/category/delete/'.$category->id) }}">
                                                <i class="fa fa-trash c-pointer link" title="Delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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

            $('.edit_category').click(function () {
                $('#category_name').val($(this).attr('data-title'));
                $('#category_position').val($(this).attr('data-position'));
                $('#category_id').val($(this).attr('data-id'));
                $('#update_category').modal('show');
            })
            $('.close-btn').click(function () {
                $('#update_category').modal('hide');
            })
        });

    </script>
@endsection
