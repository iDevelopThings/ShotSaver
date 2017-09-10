@extends('layouts.app')

@section('content')
    <div class="file-container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    @if($file->fileType() === 'image')
                        <img src="{{$file->link}}" alt="" class="img-responsive" style="width: 100%;">
                    @elseif($file->fileType() === 'video')
                        <video src="{{$file->link}}" style="width: 100%;" controls="true"></video>
                    @elseif($file->fileType() === 'audio')
                        <audio src="{{$file->link}}" style="width: 100%;" controls="true"></audio>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
