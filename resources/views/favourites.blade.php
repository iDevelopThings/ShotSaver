@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        @if($favourites->first())
            @foreach($favourites->chunk(4) as $chunk)
                <div class="row">
                    @foreach($chunk as $upload)
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Type <strong>{{ucfirst($upload->favourable->fileType())}}</strong>
                                </div>
                                <div class="image-preview"
                                     style="background-image: url('{{$upload->favourable->previewImage()}}');">
                                    <div>
                                        <div class="preview">
                                            <a href="{{$upload->favourable->link()}}" target="_blank">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <ul class="list-inline list-unstyled">
                                        <li>
                                            Size <strong>{{$upload->favourable->size()}}</strong> MB
                                        </li>
                                        <li>
                                            Uploaded
                                            <strong>{{$upload->favourable->created_at->diffForHumans()}}</strong>
                                        </li>
                                        {{-- @if(!$isImage)
                                             <li>
                                                 Link <a href="{{$upload->favourable->link()}}" target="_blank"><i
                                                             class="fa fa-external-link"></i> {{str_limit($upload->favourable->link, 20)}}
                                                 </a>
                                             </li>
                                         @endif--}}
                                    </ul>
                                    @if(isset($upload->favourable->name))
                                        <p style="word-wrap: break-word ">{{$upload->favourable->description}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="text-center">
                {!! $favourites->render() !!}
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
