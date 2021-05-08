@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row px-4">
            <div class="col-sm-12 col-md-8 order-1">
                <div class="row">
                    <div class="col border rounded py-5 text-center">
                        <h2 class="mb-3">ICPC Dhaka Regional 2020 Online Preliminary Contest - Hosted by CSE, DU</h2>
                        <button class="btn btn-primary">Invited Only</button>
                        <button class="btn btn-secondary">Standing</button>
                        <p class="mt-3">Contest Status: 09:30 AM 20 September 2021</p>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col border rounded py-3">
                        <h3>Upcoming Contests</h3>
                        <div class="w-100 px-2 py-3">
                            <p class="m-2 link c-pointer">MIST NCPC 2021 Mock</p>
                            <p class="m-2 link c-pointer">MIST NCPC 2021</p>
                            <p class="m-2 link c-pointer">UPA-CSE 100 Fall'21 Takeoff Contest</p>
                            <p class="m-2 link c-pointer">IIUC Programming Contest 2021</p>
                            <p class="m-2 link c-pointer">ICPC Asia West Continent Final Contest 2021</p>
                            <p class="m-2 link c-pointer">DUI Take-off Programming contest, Fall 2021</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col border rounded py-3">
                        <h3 class="mb-1">ICPC Dhaka Regional 2021</h3>
                        <p class="mt-0">By Afzal Shorif, 20 Jun 2021</p>
                        <div class="w-100 px-2 py-3">
                            <p>Hi everyone!

                                ICPC Dhaka Regional 2021 will take place on 20 September 2021 at 09:30 AM
                                Selected team are listed <a href="#">here</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 order-2 mb-5 pl-4">

                <div class="col border rounded p-0">
                    <div class="w-100 border-bottom rounded-top p-2 bg-custom-dark text-orange border-custom-dark">Information</div>
                    <div class="w-100 px-2 py-3">
                        <p class="m-2 link c-pointer">How to be a Setter</p>
                        <p class="m-2 link c-pointer">How to participate a contest</p>
                        <p class="m-2 link c-pointer">How to organize a contest</p>
                    </div>
                </div>

                <div class="col border rounded p-0 mt-4">
                    <div class="w-100 border-bottom rounded-top p-2 bg-custom-dark text-orange">Recent Contest</div>
                    <div class="w-100 px-2 py-3">
                        <p class="m-2 link c-pointer">MIST NCPC 2020</p>
                        <p class="m-2 link c-pointer">MIST NCPC 2020 Mock</p>
                        <p class="m-2 link c-pointer">UPA-CSE 100 Fall'19 Takeoff Contest</p>
                        <p class="m-2 link c-pointer">IIUC Programming Contest 2020</p>
                        <p class="m-2 link c-pointer">ICPC Asia West Continent Final Contest 2019</p>
                        <p class="m-2 link c-pointer">DUI Take-off Programming contest, Fall 2019</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
