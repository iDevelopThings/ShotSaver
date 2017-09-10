@extends('layouts.app')

@section('meta')
    <meta property="og:image" content="{{$file->link}}">
    <meta property="og:url" content="{{$file->link}}"/>
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
