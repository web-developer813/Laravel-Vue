@extends('volunteers.forprofits.profile-layout')

@section('profile-content')
	<div class="flex">
		<div class="map-container">
			<google-map lat="{{ $forprofit->location_lat }}" lng="{{ $forprofit->location_lng }}" :controls="true"></google-map>
		</div>
		<div class="about">
			@if($forprofit->location)
				<div class="about__line about__address">
					<div class="about__label">Address</div>
					{{ $forprofit->full_address }}
				</div>
				@endif
			@if($forprofit->formatted_phone)
				<div class="about__line about__phone">
					<div class="about__label">Phone</div>
				<a href="tel:{{ $forprofit->formatted_phone }}">{{ $forprofit->formatted_phone }}</a>
					</i>
				</div>
			@endif
			<div class="about__line about__email">
				<div class="about__label">Email</div>
				<a href="mailto:{{ $forprofit->email }}" target="_blank">{{ $forprofit->email }}</a>
			</div>
		</div>
	</div>
@stop