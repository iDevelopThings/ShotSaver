@extends('layouts.app')

@section('meta')

    <meta content="{{request()->fullUrl()}}" property="og:url" />
    @if($type == 'image')
        <meta name="twitter:card" content="summary_large_image" />
        <meta property="og:image" content="{{$file->link}}">
        <meta property="og:image:type" content="{{$file->mime_type}}">
        <meta property="og:type" content="image">

        @if($dimensions != null)
            <meta property="og:image:width" content="{{$dimensions['width']}}" />
            <meta property="og:image:height" content="{{$dimensions['height']}}" />
        @endif

        {{--@elseif($type == 'video')

            <meta property="og:video" content="{{$file->link}}">
            <meta property="og:video:width" content="1920">
            <meta property="og:video:height" content="1080">
            <meta property="og:video:type" content="application/mp4">
            <meta property="og:type" content="video.other">
            <meta property="og:image" content="{{$file->thumbnail_url}}">
        @else--}}
        <meta content="article" property="og:type" />
    @endif

    <meta name="theme-color" content="#0084da">
    <meta name="twitter:site" content="@ShotSaver" />
    <meta name="twitter:title" content="ShotSaver" />
    <meta name="og:site_name" content="ShotSaver" />
    <meta name="twitter:description" content="An {{$type}} uploaded to ShotSaver by {{$file->user->name}}" />
    <meta name="description" content="Check out this video on Streamable using your phone, tablet or desktop.">
    <meta property="og:description" content="An {{$type}} uploaded to ShotSaver by {{$file->user->name}}" />

    @if($type == 'video')
        <meta property="og:type" content="video.other">
        <meta property="og:image"
              content="{{$file->thumbnail_url}}" />
        <meta property="og:image:secure_url"
              content="{{$file->thumbnail_url}}" />
        <meta property="og:image:type" content="image/png" />
        <meta property="og:image:width" content="1280">
        <meta property="og:image:height" content="720">

        <meta property="og:updated_time" content="{{$file->created_at->toAtomString() }}" />
        <meta property="og:video"
              content="{{$file->link}}">
        <meta property="og:video:url"
              content="{{$file->link}}">
        <meta property="og:video:secure_url"
              content="{{$file->link}}">
        <meta property="og:video:type" content="video/mp4">
        <meta property="og:video:width" content="1280">
        <meta property="og:video:height" content="720">

        <meta name="twitter:card" content="player">
        <meta name="twitter:site" content="@ShotSaver">
        <meta name="twitter:image"
              content="{{$file->thumbnail_url}}">
        <meta name="twitter:player:width" content="1280">
        <meta name="twitter:player:height" content="720">
        <meta name="twitter:player" content="{{route('file-twitter', ['file' => $file->name])}}">
        <meta name="twitter:player:stream"
              content="{{$file->link}}">
        <meta name="twitter:player:stream:content_type" content="video/mp4">
    @endif



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
                            @if($file->platform ==='streamable')
                                {!! $file->embed !!}
                            @else
                                <video src="{{$file->link}}" style="width: 100%;" controls="true"></video>
                            @endif
                        @elseif($type === 'audio')
                            <audio src="{{$file->link}}" style="width: 100%;" controls="true"></audio>
                        @elseif($type === 'compressed')
                            <div class="text-center">
                                <a href="{{$file->link}}" class="btn btn-lg btn-primary" download>
                                    <i class="fa fa-download"></i> Download File
                                </a>
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
                        @if (Session::has('success'))
                            <div class="alert alert-success">{!! Session::get('success') !!}</div>
                        @endif
                        @if (Session::has('failure'))
                            <div class="alert alert-danger">{!! Session::get('failure') !!}</div>
                        @endif
                        <ul class="list-inline clearfix" style="font-size: 22px;">
                            <li>
                                File Type <strong>{{ucfirst($type)}}</strong>
                            </li>
                            <li>
                                Uploaded <strong>{{$file->created_at->diffForHumans()}}</strong>
                            </li>
                            <li class="pull-right">
                                <a href="{{$file->link}}" class="btn btn-primary" download>
                                    <i class="fa fa-download"></i> Download file
                                </a>
                            </li>
                            <li class="pull-right">
                                @if(auth()->guest())
                                    <button class="btn btn-default"
                                            disabled
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Log in or Register to favourite this file.">
                                        <i class="fa fa-heart"></i> Favourite file
                                    </button>
                                @else
                                    <form action="{{route('favourite', $file->name)}}" method="post">
                                        {!! csrf_field() !!}
                                        <?php
                                        $hasFavourited = auth()->user()->favourites()
                                            ->where('favourable_id', $file->id)
                                            ->where('favourable_type', \App\Models\FileUpload::class)
                                            ->first();
                                        ?>
                                        @if(!$hasFavourited)
                                            <button class="btn btn-default">
                                                <i class="fa fa-heart"></i> Favourite file
                                            </button>
                                        @else
                                            <button class="btn btn-danger">
                                                <i class="fa fa-heart"></i> Un favourite file
                                            </button>
                                        @endif
                                    </form>
                                @endif
                                <?php $faves = $file->favourites()->count(); ?>
                                <small style="font-size: 13px;">
                                    Favourited by <strong>{{$faves}}</strong> other{{$faves === 1 ? '' : '\'s'}}
                                </small>
                            </li>
                        </ul>
                        <ul class="list-inline text-muted">
                            {{--@if($type === 'video')
                                <li>
                                    Length <strong>{{$dimensions['length']}}</strong>
                                </li>
                                <li>
                                    FPS <strong>{{$dimensions['fps']}}</strong>
                                </li>
                            @endif
                            <li>Width <strong>{{$dimensions['width']}}</strong></li>
                            <li>Height <strong>{{$dimensions['height']}}</strong></li>--}}
                            <li>File Size(MB) <strong>{{$file->size()}}</strong></li>
                            <li>Views <strong>{{$file->views()->count()}}</strong></li>
                        </ul>
                        <hr>

                        @if(!isset($file->description))
                            @if(Auth::check() && Auth::user()->id == $file->user->id)
                                <form method="POST" action="{{route('addFileDescription', $file->name)}}">
                                    {{csrf_field() }}
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" rows="2" style="resize: vertical;"
                                                  placeholder="Add a description for this file"
                                                  class="form-control" maxlength="255"></textarea>
                                    </div>
                                    <button class="btn btn-success pull-right">
                                        <i class="fa fa-header"></i> Add Description
                                    </button>
                                </form>
                            @endif
                        @else
                            @if(Session::has('edit_description'))
                                <form method="POST" action="{{route('updateFileDescription', $file->name)}}">
                                    {{csrf_field() }}
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" rows="2" style="resize: vertical;"
                                                  placeholder="Add a description for this file"
                                                  class="form-control" maxlength="255">{{$file->description}}</textarea>
                                    </div>
                                    <button class="btn btn-success pull-right">
                                        <i class="fa fa-edit"></i> Update Description
                                    </button>
                                </form>
                            @else
                                <label for="description">Description</label>
                                <p style="word-wrap: break-word ">{{$file->description}}</p>
                                @if(Auth::check() && Auth::user()->id == $file->user->id)
                                    <form method="POST" action="{{route('viewEditFileDescription', $file->name)}}">
                                        {{csrf_field() }}
                                        <button class="btn btn-warning pull-right">
                                            <i class="fa fa-pencil"></i> Edit Description
                                        </button>
                                    </form>
                                    <form method="POST" action="{{route('removeFileDescription', $file->name)}}">
                                        {{csrf_field() }}
                                        <button class="btn btn-danger pull-right" style="margin-right: 10px;">
                                            <i class="fa fa-trash"></i> Remove Description
                                        </button>
                                    </form>

                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
