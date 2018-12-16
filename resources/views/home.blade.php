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
                    <div class="panel-heading"><strong>How to upload</strong></div>

                    <div class="panel-body" style="border-bottom: 1px solid #d3e0e9;">
                        <h4>
                            Download ShareX
                        </h4>
                        <p>
                            You can use ShareX to easily upload images or videos directly from your computer, this is
                            for windows users only.
                        </p>
                        <a target="_blank" href="https://getsharex.com/">Download ShareX from their website</a>

                    </div>
                    <div class="panel-heading">
                        <strong> Configure ShareX or another client</strong>
                    </div>
                    <div class="panel-body">

                        <p>Configure your destination in ShareX</p>
                        <img src="https://storage.idevelopthings.com/shotsaver/b83fe677791d5fe9fac5fb050f485a0b.png"
                             alt="configure destination" class="img-responsive">

                        <br>
                        <br>

                        <p>
                            Once you clicked destinations, you can then add the parameters as configured in this
                            printscreen.

                            <br>
                            <br>

                            Add your api key in the box where the blue arrow is on the right, then click add, where the
                            blue arrow is on the left.

                        </p>
                        <img src="https://storage.idevelopthings.com/shotsaver/424f9d9dcdc69438089bf681d5c9cff0.png"
                             alt="configure the uploader"
                             class="img-responsive">

                        <br>
                        <br>
                        <div class="form-group">
                            <label for="">"Request type"</label>
                            <input disabled type="text" value="POST" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">"Request URL"</label>
                            <input disabled type="text" value="{{url('')}}/api/upload" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Argument: api_token</label>
                            <input disabled type="text" value="{{Auth::user()->api_token}}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css" />
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>

    <script>
        Dropzone.options.upload = {
            paramName   : "d",
            maxFilesize : 1000,
            init        : function () {
                this.on("success", function (param, response) {
                    $('#upload-results').append('<div class="alert alert-success">File successfully uploaded <a href="' + response + '">link</a></div>');
                });
            }
        };
    </script>
@endsection