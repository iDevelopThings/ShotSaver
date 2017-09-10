@extends('layouts.app')

@section('meta')
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@ShotSaver"/>
    <meta name="twitter:title" content="ShotSaver Image"/>
    <meta name="twitter:description" content="No Description."/>
    <meta name="twitter:image" content="{{$file->link}}"/>

    <meta content="article" property="og:type"/>
    <meta content="{{request()->fullUrl()}}" property="og:url"/>
    <meta content="{{$file->link}}" property="og:image"/>
    @if($dimensions != null)
        <meta content="{{$dimensions['width']}}" property="og:image:width"/>
        <meta content="{{$dimensions['height']}}" property="og:image:height"/>
    @endif
    <meta content="An image uploaded to ShotSaver by {{$file->user->name}}" property="og:description"/>
    <link href="{{$file->link}}" rel="image_src"/>
    <meta content="summary_large_image" name="twitter:card"/>
    <meta content="@ShotSaver" name="twitter:site"/>
    <meta content="ShotSaver" name="twitter:title"/>
    <meta content="An image uploaded to ShotSaver by {{$file->user->name}}" name="twitter:description"/>
    <meta content="{{$file->link}}" name="twitter:image"/>

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
                        <ul class="list-inline" style="font-size: 22px;">
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
