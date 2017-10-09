@extends('volunteers.layout')

@section('page_id', 'opportunities-show')

@section('content')
	<div class="single-column single-column--wide">
		<div class="opportunity__header">
			{{-- title --}}
			<h1 class="opportunity__title">
				{{ $opportunity->title }}
				@if($opportunity->closed)
				<small>(closed)</small>
				@endif
			</h1>

			{{-- meta --}}
			<div class="opportunity__meta">
				Posted by <a href="{{ route('nonprofits.show', $opportunity->nonprofit_id) }}">{{ $opportunity->nonprofit->name}}</a> &middot; {{ $opportunity->present()->date }}
			</div>
		</div>
	</div>

	<div class="two-columns">

		{{-- main content --}}
		<div class="single-column">
				
			@if($opportunity->hasImage())
				<div class="opportunity__image" style="background-image: url({{ $opportunity->image }});"></div>
			@endif
			
			<div class="opportunity__content">
				{{-- description --}}
				<div class="opportunity__description">{!! $opportunity->present()->description !!}</div>
				
				{{-- nonprofit --}}
				<div class="opportunity__nonprofit">
					<h2>About {{ $opportunity->nonprofit->name}}</h2>
					{{ $opportunity->nonprofit->description }}
				</div>
			</div>
		</div>

		@include('volunteers.opportunities.show._sidebar')
	</div>
@stop