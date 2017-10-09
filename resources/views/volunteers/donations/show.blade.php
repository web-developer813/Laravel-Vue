@extends('volunteers.layout')

@section('page_id', 'donations-show')

@section('content')
	<div class="single-column">
		<div class="thank-you">
			<h1>Thank you for the donation!</h1>
			<p>
				You donated {{ $donation->points }} {{ str_plural('point', $donation->points)}} to {{ $donation->nonprofit->name }}
			</p>
		</div>
		<div class="menu">
			<a href="{{ route('newsfeed') }}" class="btn btn--large btn--default">Continue</a>
		</div>
	</div>
@stop