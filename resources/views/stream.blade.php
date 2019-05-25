@extends('layouts.app')

@section('content')
    <div class="home-header">
        <div class="container">
            <h1>
                <span class="blue">Shot</span>Saver
            </h1>


            <video id="video" controls preload="auto" width="640" height="360">
                <source src="/stream" type='video/mp4' />
            </video>

        </div>
    </div>
@endsection
