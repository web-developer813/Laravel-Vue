@extends('html5')

@section('css')
{!! style_ts('/css/app.css') !!}
{!! style_cdn('/css/skins/orange.min.css') !!}
@stop

@section('header')
@include('app._global-header')
@stop

@section('page-content')
<div class="page">
	@hasSection('page-aside')
	@yield('page-aside')
	<div class="page-main">
		@yield('page-header')
		<div class="page-content container-fluid">
			@yield('content')
		</div>
	</div>
	@else
	@yield('page-header')
	<div class="page-content container-fluid">
		@yield('content')
	</div>
	@endif
</div>
@stop

@section('footer')
@include('app.footer')
@include('app.components.chat')
@stop

@section('scripts')
{!! script_ts('/js/app.js') !!}
@stop