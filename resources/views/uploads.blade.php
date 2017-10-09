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
                @if($uploads->count())
                    @foreach($uploads->chunk(4) as $chunk)
                        <div class="row">
                            @foreach($chunk as $upload)
                                <div class="col-md-3">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Type <strong>{{ucfirst($upload->fileType())}}</strong>
                                        </div>
                                        <div class="image-preview"
                                             style="background-image: url('{{$upload->previewImage()}}');">
                                            <div>
                                                <div class="preview">
                                                    <a href="{{$upload->link()}}" target="_blank">
                                                        <i class="fa fa-external-link"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <ul class="list-inline list-unstyled">
                                                <li>
                                                    Size <strong>{{$upload->size()}}</strong> MB
                                                </li>
                                                <li>
                                                    Uploaded <strong>{{$upload->created_at->diffForHumans()}}</strong>
                                                </li>
                                                {{-- @if(!$isImage)
                                                     <li>
                                                         Link <a href="{{$upload->link()}}" target="_blank"><i
                                                                     class="fa fa-external-link"></i> {{str_limit($upload->link, 20)}}
                                                         </a>
                                                     </li>
                                                 @endif--}}
                                            </ul>
                                            @if(isset($upload->name))
                                                <p style="word-wrap: break-word ">{{$upload->description}}</p>
                                            @endif
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
        </div>


    </div>
@endsection
