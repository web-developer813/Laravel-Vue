@extends('volunteers.nonprofits.profile-layout')

@section('profile-content')
	<div class="flex">
		<div class="map-container">
			<google-map lat="{{ $nonprofit->location_lat }}" lng="{{ $nonprofit->location_lng }}" :controls="true"></google-map>
		</div>
		<div class="about">
			@if($nonprofit->location)
				<div class="about__line about__address">
					<div class="about__label">Address</div>
					{{ $nonprofit->full_address }}
				</div>
				@endif
			@if($nonprofit->formatted_phone)
				<div class="about__line about__phone">
					<div class="about__label">Phone</div>
				<a href="tel:{{ $nonprofit->formatted_phone }}">{{ $nonprofit->formatted_phone }}</a>
					</i>
				</div>
			@endif
			<div class="about__line about__email">
				<div class="about__label">Email</div>
				<a href="mailto:{{ $nonprofit->email }}" target="_blank">{{ $nonprofit->email }}</a>
			</div>
		</div>
	</div>
@stop