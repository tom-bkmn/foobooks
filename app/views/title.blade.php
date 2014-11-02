@extends('_master')

@section('title')
    Toms first template
@stop

@section('bodyContent_1')
     This is some page content specified in title.blade.php
@stop

@section('dynamic')
    This content was provided from the url {{$item}}
@stop

@section('color')
    #{{$item}}
@stop