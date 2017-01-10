@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
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
