@extends('layouts.app')

@section('meta')

    <meta content="article" property="og:type"/>
    <meta content="{{request()->fullUrl()}}" property="og:url"/>
    @if($type == 'image')
        <meta name="twitter:card" content="summary"/>
        <meta content="{{$file->link}}" property="og:image"/>
        <link href="{{$file->link}}" rel="image_src"/>
        <meta content="summary_large_image" name="twitter:card"/>
        <meta content="{{$file->link}}" name="twitter:image"/>
        @if($dimensions != null)
            <meta content="{{$dimensions['width']}}" property="og:image:width"/>
            <meta content="{{$dimensions['height']}}" property="og:image:height"/>
        @endif
    @elseif($type == 'video')
        <meta name="twitter:card" content="player"/>
        <meta name="twitter:player" content="{{$file->link}}"/>
        <meta content="{{$file->link}}" property="og:video"/>
        <link href="{{$file->link}}" rel="video_src"/>
        <meta content="summary_large_video" name="twitter:card"/>
        <meta content="{{$file->mime_type}}" name="twitter:player:stream:content_type"/>
        <meta content="{{$type}}" name="twitter:player:stream"/>
        <meta content="{{$file->link}}" name="twitter:video"/>
        <meta content="{{$dimensions['width']}}" property="og:image:width"/>
        <meta content="{{$dimensions['height']}}" property="og:image:height"/>
        <meta content="{{$dimensions['width']}}" property="twitter:player:width"/>
        <meta content="{{$dimensions['height']}}" property="twitter:player:height"/>
    @endif
    <meta content="An {{$type}} uploaded to ShotSaver by {{$file->user->name}}" property="og:description"/>
    <meta content="@ShotSaver" name="twitter:site"/>
    <meta content="ShotSaver" name="twitter:title"/>
    <meta content="An {{$type}} uploaded to ShotSaver by {{$file->user->name}}" name="twitter:description"/>

@endsection

@section('content')

    <div class="file-upload">
        <div class="file-container">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        @if($type === 'image')
                            <div class="preview">
                                <img src="{{$file->link}}" alt="" class="img-responsive center-block">
                                <div class="overlay">
                                    <a href="{{$file->link}}" target="_blank">
                                        <i class="fa fa-external-link"></i>
                                    </a>
                                </div>
                            </div>
                        @elseif($type === 'video')
                            <video src="{{$file->link}}" style="width: 100%;" controls="true"></video>
                        @elseif($type === 'audio')
                            <audio src="{{$file->link}}" style="width: 100%;" controls="true"></audio>
                        @elseif($type === 'compressed')
                            <div class="text-center">
                                <button class="btn btn-lg btn-primary">
                                    <i class="fa fa-download"></i> Download
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="file-details">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <ul class="list-inline clearfix" style="font-size: 22px;">
                            <li>
                                File Type <strong>{{ucfirst($type)}}</strong>
                            </li>
                            <li>
                                Uploaded <strong>{{$file->created_at->diffForHumans()}}</strong>
                            </li>
                            <li class="pull-right">
                                <button class="btn btn-primary">
                                    <i class="fa fa-download"></i> Download File
                                </button>
                            </li>
                        </ul>
                        @if($type === 'video')
                            <ul class="list-inline text-muted">
                                <li>
                                    Length <strong>{{$dimensions['length']}}</strong>
                                </li>
                                <li>
                                    FPS <strong>{{$dimensions['fps']}}</strong>
                                </li>
                            </ul>
                        @endif
                        <hr>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description"
                                      placeholder="Add a description for this file" class="form-control"></textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
