@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">

                        <form id="upload" action="{{url('')}}/api/upload?api_token={{Auth::user()->api_token}}"
                              class="dropzone"></form>
                    </div>
                    <div class="panel-footer" id="upload-results">

                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <h3 class="text-center">
                            Your Api Key
                        </h3>
                        <input disabled type="text" style="width: 300px;" value="{{Auth::user()->api_token}}"
                               class="form-control center-block text-center">
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>
                            How to upload
                        </h3>
                    </div>

                    <div class="panel-body">
                        <label for="">Upload URL</label>
                        <input disabled type="text" style="width: 300px;" value="{{url('')}}/api/upload"
                               class="form-control">
                        <h3>
                            Arguments
                        </h3>
                        <strong>api_token</strong> : <strong>{{Auth::user()->api_token}}</strong>
                        <br>
                        File Param: <strong>d</strong><br>
                        Method: <strong>POST</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css"/>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>

    <script>
		Dropzone.options.upload = {
			paramName   : "d",
			maxFilesize : 10,
			init        : function ()
			{
				this.on("success", function (param, response)
				{
					$('#upload-results').append('<div class="alert alert-success">File successfully uploaded <a href="' + response + '">link</a></div>');
				});
			}
		};
    </script>
@endsection