@extends('layouts.app')

@section('content')
    <div class="container">


        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="text-center">
                            {{$spaceUsed}}
                            <br>
                            <small>
                                Space Used(MB)
                            </small>
                        </h1>
                    </div>
                    <div class="col-md-6">
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
        </div>

        @if($uploads->count())
            @foreach($uploads->chunk(4) as $chunk)
                <div class="row">
                    @foreach($chunk as $upload)
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Type <strong>{{$upload->type}}</strong>
                                </div>
								<?php
								$isImage = false;
								if ($upload->type == 'png' || $upload->type == 'jpeg' || $upload->type == 'jpg' || $upload->type == 'gif')
									$isImage = true;
								?>
                                @if($isImage)
                                    <div class="image-preview" style="background-image: url('{{$upload->link}}');">
                                        <div>
                                            <div class="preview">
                                                <a href="{{$upload->link}}" target="_blank"><i
                                                            class="fa fa-external-link"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="panel-footer">
                                    <ul class="list-inline list-unstyled">
                                        <li>
                                            Size <strong>{{$upload->size()}}</strong> MB
                                        </li>
                                        <li>
                                            Uploaded <strong>{{$upload->created_at->diffForHumans()}}</strong>
                                        </li>
                                        @if(!$isImage)
                                            <li>
                                                Link <a href="{{$upload->link}}" target="_blank"><i
                                                            class="fa fa-external-link"></i> {{str_limit($upload->link, 20)}}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="text-center">
                {!! $uploads->render() !!}
            </div>
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
