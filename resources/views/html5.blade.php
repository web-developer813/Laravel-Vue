<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<meta name="description" content="@yield('meta-description')">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="author" content="">
	
	<title>
		@yield('title', 'Tecdonor &middot; Rewards Program For Volunteers')
	</title>
	
	{{-- styles --}}
	@yield('css')
	{!! style_cdn('/vendor/animsition/animsition.min.css') !!}
	{!! style_cdn('/vendor/asscrollable/asScrollable.min.css') !!}
	{!! style_cdn('/vendor/switchery/switchery.min.css') !!}
	{!! style_cdn('/vendor/slidepanel/slidePanel.min.css') !!}
	{!! style_cdn('/vendor/waves/waves.min.css') !!}
	{!! style_cdn('/vendor/chartist/chartist.min.css') !!}
	{!! style_cdn('/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.min.css') !!}
	{{ HTML::style('https://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic') }}
	{!! style_cdn('/fonts/web-icons/web-icons.min.css') !!}
	{!! style_cdn('/fonts/brand-icons/brand-icons.min.css') !!}
	{!! style_cdn('/fonts/material-design/material-design.min.css') !!}
    <!--[if lt IE 9]>
      <script src="/vendor/html5shiv/html5shiv.min.js"></script>
      <![endif]-->
    <!--[if lt IE 10]>
      <script src="/vendor/media-match/media.match.min.js"></script>
      <script src="/vendor/respond/respond.min.js"></script>
      <![endif]-->
    <!-- Scripts -->

    {{-- favicon --}}
    <link rel="shortcut icon" href="/img/favicon.png" type="image/png" />
	
	{!! script_cdn('/vendor/modernizr/modernizr.min.js') !!}
	{!! script_cdn('/vendor/breakpoints/breakpoints.min.js') !!}
	<script>
	Breakpoints();
	</script>
	{{-- head --}}
    @yield('head')
    @include('app.scripts.mode')
</head>
<body id="@yield('page_id')" class="@yield('body_class','animsition')">
    <!--[if lt IE 8]>
          <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
	{{-- app --}}
	<div id="body" @yield('body-atts')>
		@yield('header')
		@yield('page-content')
		@yield('templates')
	    @yield('footer')
    </div>
	{{-- core --}}
	@yield('scripts')

	{{-- page --}}
	@yield('page_script')
	<script>
		jQuery(document).ready(function() {
			
		});
	</script>

	{!! php_to_js('googleApiKey', getenv('GOOGLE_API_KEY')) !!}
	@include('app.scripts.google-analytics')
	@include('app.scripts.intercom')
	@include('app.scripts.appcues')
	@include('app.templates.message')
	@yield('body_close')
</body>
</html>