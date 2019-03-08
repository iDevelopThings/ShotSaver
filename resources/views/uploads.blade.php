@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-3">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1 class="text-center">
                            <span class="@if($spaceUsed >= 100) text-danger @endif">{{$spaceUsed}}/100</span>
                            <br>
                            <small>
                                Space Used(MB)
                            </small>
                        </h1>
                    </div>
                    <div class="progress" style="margin-bottom: 0; border-radius: 0;">
                        <div class="progress-bar @if($spaceUsed >= 100) progress-bar-danger @endif" role="progressbar"
                             aria-valuenow="{{$spaceUsed}}" aria-valuemin="0"
                             aria-valuemax="{{$spaceUsed < 100 ? 100 : $spaceUsed}}" style="width: {{$spaceUsed}}%;">
                            {{$spaceUsed}}%
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1 class="text-center">
                            {{$uploadsCount}}
                            <br>
                            <small>
                                Uploads
                            </small>
                        </h1>
                    </div>
                </div>

            </div>
            <div class="col-md-9">

                    <my-uploads :count="{{$uploadsCount}}"></my-uploads>

            </div>
        </div>


    </div>
@endsection
