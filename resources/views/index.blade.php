@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row px-4">
            <div class="col-sm-12 col-md-8 order-1">
                <div class="row">
                    <div class="col border rounded py-5 text-center">
                        <h2 class="mb-3">Contest Name</h2>
                        <button class="btn btn-primary">Invited Only</button>
                        <button class="btn btn-secondary">Standing</button>
                        <p class="mt-3">Contest Status: start time</p>
                    </div>
                </div>
                <div class="row mt-5">

                </div>
            </div>
            <div class="col-sm-12 col-md-4 order-2 mb-5 pl-4">

                <div class="col border border-info rounded p-0" style="border-color: #6cb2eb4f !important;">
                    <div class="w-100 border-bottom border-info p-2" style="background: #6cb2eb4f !important; border-color: #6cb2eb4f !important;">Information</div>
                    <div class="w-100 px-2 py-3">
                        <p class="m-2 link c-pointer">How to be a Setter</p>
                        <p class="m-2 link c-pointer">How to participate a contest</p>
                        <p class="m-2 link c-pointer">How to organize a contest</p>
                    </div>
                </div>

                <div class="col border border-info rounded p-0 mt-4" style="border-color: #6cb2eb4f !important;">
                    <div class="w-100 border-bottom border-info p-2" style="background: #6cb2eb4f !important; border-color: #6cb2eb4f !important;">Recent Contest</div>
                    <div class="w-100 px-2 py-3">
                        <p class="m-2 link c-pointer">Contest Title</p>
                        <p class="m-2 link c-pointer">Contest Title</p>
                        <p class="m-2 link c-pointer">Contest Title</p>
                        <p class="m-2 link c-pointer">Contest Title</p>
                        <p class="m-2 link c-pointer">Contest Title</p>
                        <p class="m-2 link c-pointer">Contest Title</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
