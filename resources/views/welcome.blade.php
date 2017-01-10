@extends('layouts.app')

@section('content')
    <div class="home-header">
        <div class="container">
            <h1>
                <span class="blue">Shot</span>Saver
            </h1>
            <p>
                Simple image sharing with api access!
            </p>
        </div>
    </div>
    <div class="home-info">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="item">
                        <h1>
                            <i class="fa fa-user-plus"></i>
                        </h1>
                        <span>Register</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item">
                        <h1>
                            <i class="fa fa-upload"></i>
                        </h1>
                        <span>Upload your files</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item">
                        <h1>
                            <i class="fa fa-code"></i>
                        </h1>
                        <span>Or use our API</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
