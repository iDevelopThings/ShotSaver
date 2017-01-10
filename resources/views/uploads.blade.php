@extends('layouts.app')

@section('content')
    <div class="container">
        @if($uploads->count())
            @foreach($uploads->chunk(4) as $chunk)
                <div class="row">
                    @foreach($chunk as $upload)
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Type <strong>{{$upload->type}}</strong>
                                </div>
                                <div class="panel-body">
                                    @if($upload->type == 'png' || $upload->type == 'jpeg' || $upload->type == 'jpg' || $upload->type == 'gif')
                                        <a href="{{$upload->link}}" target="_blank">
                                            <img src="{{$upload->link}}" class="img-responsive" style="width: 100%;"
                                                 alt="">
                                        </a>
                                    @endif

                                    Link <a href="{{$upload->link}}" target="_blank"><i
                                                class="fa fa-external-link"></i> {{str_limit($upload->link, 20)}}</a>
                                    <br>
                                    Uploaded <strong>{{$upload->created_at->diffForHumans()}}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
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
@endsection
