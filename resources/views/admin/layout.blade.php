@extends('html5')

@section('css')
{!! style_ts('/css/app.css') !!}
@stop

@section('page_id', 'admin')

@section('body')
	@include('admin._header')
	@yield('page-header')
	@yield('content')
@stop

@section('scripts')
{!! script_ts('/js/app.js') !!}
@stop