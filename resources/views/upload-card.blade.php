<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            Type <strong>{{ucfirst($upload->fileType())}}</strong>
        </div>

        <div class="pull-right">
            <form onsubmit="return confirm('Are you sure you wish to delete this file?')"
                  action="{{route('delete', ['file' => $upload->name, 'page' => request('page', 1)])}}"
                  method="post">
                {!! csrf_field() !!}
                <button class="btn btn-danger btn-xs">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        </div>
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
        <ul class="list-inline list-unstyled" style="margin: 0;">
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