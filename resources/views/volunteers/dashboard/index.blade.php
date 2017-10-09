 @extends('volunteers.layout')

@section('page_id', 'dashboard')
@section('body_class','animsition page-aside-static page-aside-right')

@section('page-aside')
	@include('volunteers.dashboard._sidebar')
@stop

@section('content')
	<create-post action="{{ secure_url('api/posts') }}" inline-template>
	@include('app.templates.create-post')
	</create-post>
	{{--<simple-feed--}}
		{{--resource-url="{{ secure_url('api/newsfeed') }}"--}}
		{{--no-results="There are currently no items in your feed. Why not follow some organizations you care about?"--}}
		{{--feed-type="realtime" inline-template>--}}
		{{--<div id="vue-wrapper">--}}
			{{--<ul class="blocks blocks-100 blocks-xxl-2 blocks-lg-2 blocks-md-1 js-masonry" v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="10" infinite-scroll-immediate-check="false">--}}
				{{--<li v-for="item in items" v-bind:key="item" class="feed-item masonry-item animation-slide-top animation-delay-300">--}}
					{{--@include('app.components.feeds._post-item')--}}
				{{--</li>--}}
			{{--</ul>--}}
			{{--<div class="row justify-content-center mt-60 mb-60">--}}
				{{--<div class="col-md-10 text-center">--}}
					{{--@include('app.components.loading')--}}
				{{--</div>--}}
			{{--</div>--}}
			{{--<div class="card card-shadow" v-show="!items.length && !loading">--}}
				{{--<div class="row justify-content-center" v-show="!items.length && !loading">--}}
					{{--<div class="col-md-10 text-center">--}}
						{{--@{{ noResults }}--}}
					{{--</div>--}}
				{{--</div>--}}
			{{--</div>--}}
		{{--</div>--}}
	{{--</simple-feed>--}}
@stop