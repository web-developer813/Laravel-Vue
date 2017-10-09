@extends('html5')

@section('css')
{!! style_ts('/css/app.css') !!}
{!! style_cdn('/css/skins/orange.min.css') !!}
@stop

@section('page_id', 'auth-layout')

@section('page-content')
<div class="page" data-animsition-in="fade-in" data-animsition-out="fade-out">
	<div class="page-content">
		@yield('content')
	</div>
</div>
@stop

@section('scripts')
{!! script_ts('/js/auth.js') !!}
@stop