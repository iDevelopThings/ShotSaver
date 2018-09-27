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

                <my-uploads-filter
                        page="{{request('page', 1)}}"
                        url="{{route('my-uploads')}}"
                        current-filter="{{request('filter_by', 'created_at')}}"
                        current-order="{{request('order', 'desc')}}"
                ></my-uploads-filter>

            </div>
            <div class="col-md-9">

                @if (Session::has('success'))
                    <div class="alert alert-success">{!! Session::get('success') !!}</div>
                @endif
                @if (Session::has('failure'))
                    <div class="alert alert-danger">{!! Session::get('failure') !!}</div>
                @endif

                <?php $count = $uploads->count(); ?>
                @if($count)
                    @if($count > 4)

                        @foreach($uploads->chunk(4) as $chunk)
                            <div class="row">
                                @foreach($chunk as $upload)
                                    <div class="col-md-3">
                                        @include('upload-card', ['upload' => $upload])
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="text-center">
                            {!! $uploads->render() !!}
                        </div>
                    @else
                        <div class="row">
                            @foreach($uploads as $upload)
                                <div class="col-md-3">
                                    @include('upload-card', ['upload' => $upload])
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h1 class="text-center">
                                You have not uploaded any files.
                            </h1>
                        </div>
                    </div>
                @endif
            </div>
        </div>


    </div>
@endsection
