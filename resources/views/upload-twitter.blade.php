@extends('layouts.app-twitter')

@section('meta')
    @include('upload.metatags', ['file' => $file, 'type' => $type])
@endsection

@section('content')
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

@endsection
