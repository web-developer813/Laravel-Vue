@extends('volunteers.layout')

@section('page_id', 'forprofits-show')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
@stop

@section('content')
	<div id="profile">
		<div class="profile-content-bg">
			<div class="single-column">

				{{-- profile information --}}
				<div class="profile__information">
					@if(current_mode() == 'nonprofit')
					<div class="donate pull-right">
						<button
							class="btn btn--primary btn--large"
							data-toggle="modal"
							data-target="#donate-modal"
							{{ (!$forprofit->monthly_points_remaining) ? 'disabled' : '' }}>Request Donation</button>
							<span class="budget">This business has {{ $forprofit->monthly_points_remaining }} {{ str_plural('point', $forprofit->monthly_points_remaining) }} left to donate this month</span>
					</div>
					@endif
					<div class="profile__header">
						@include('app.components.profile-photo', [
							'source' => $forprofit->profilePhoto, 'name' => $forprofit->name
						])
						<div class="profile-header-content">
							<h1 class="profile__name page-title">{{ $forprofit->name }}</h1>
							<h2 class="profile__mission">{{ $forprofit->present()->mission }}</h2>
							<div class="profile__location">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
								{{ $forprofit->present()->location }}
								@if($forprofit->website_url)
									&middot; <a href="{{ $forprofit->website_url }}" target="_blank" rel="nofollow" class="profile__website">{{ $forprofit->formatted_website_url }}</a>
								@endif
							</div>
						</div>
						@if($forprofit->description)
							<div class="profile__description">{!! $forprofit->present()->description !!}</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		
		<div class="single-column">
				
			{{-- profile tabs --}}
			<ul class="profile-tabs">
				<li><a href="{{ route('forprofits.show', $forprofit->id) }}"
					class="{{ nav_active('forprofits.show') }}">Coupons</a></li>
				<li><a href="{{ route('forprofits.show.donations', $forprofit->id) }}"
					class="{{ nav_active('forprofits.show.donations') }}">Donations</a></li>
				<li><a href="{{ route('forprofits.show.about', $forprofit->id) }}"
					class="{{ nav_active('forprofits.show.about') }}">About us</a></li>
			</ul>
				
			@yield('profile-content')
		</div>
	</div>
@stop

@if(current_mode() == 'nonprofit')
@section('templates')
	<div id="donate-modal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route('api.nonprofits.donations.store', [$authNonprofit->id, $forprofit->id]) }}" method="post" @submit.prevent="submitAjaxForm">
						{{ csrf_field() }}
					<div class="modal-header">
						<h1 class="modal-title">{{ $forprofit->name }} has {{ $forprofit->monthly_points_remaining }} {{ str_plural('point', $forprofit->monthly_points_remaining) }} left to donate this month</h1>
					</div>
					<div class="modal-body">
						{{-- <input name="points" type="text"> --}}
						@include('app.components.form-field', [
							'field' => 'points', 'label' => 'How many points would you like to request?',
							'input' => Form::number('points', null, ['class' => 'form__field'])
						])
					</div>
					<div class="modal-footer">
							<button type="button" class="btn btn--link" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn--primary">
								<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
								Request Donation</button>
					</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
@stop
@endif