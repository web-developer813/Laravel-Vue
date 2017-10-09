@extends('html5')

@section('css')
{!! style_ts('/css/app.css') !!}
@stop

@section('header')
@include('app._global-header')
@stop

@section('page-content')
<div class="page">
	<div class="page-main">
		@yield('page-header')
		<div class="page-content container-fluid">
			@yield('content')
		</div>
	</div>
</div>
@stop

@yield('templates')

@section('footer')
@include('app.footer')
@stop

@section('scripts')
{!! script_ts('/js/app.js') !!}
@stop
