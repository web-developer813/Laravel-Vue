@extends('forprofits.layout')

@section('page_id', 'forprofits-admin-donations-edit')

@section('page-header')
	<div class="page-header">
		<div class="single-column">
			<h2>Donation request from {{ $donation->nonprofit->name }}</h2>
		</div>
	</div>
@stop

@section('content')
	<div class="single-column">

		{{-- status --}}
		<section class="box box__status status--{{ $donation->css_status }}">
			@if($donation->fulfilled)
				<strong class="response__status">This donation request has been fulfilled.</strong>
			@else
				<strong class="response__status">This donation request is pending.</strong>
			@endif
		</section>

		{{-- contact --}}
		<section class="box">
			<p><strong>Request Details</strong></p>
			<p>Donation Request Value: USD {{ $donation->points }} </p>
			<p>Nonprofit Organization: {{ $donation->nonprofit->name }}</p>
			<p>Nonprofit Tax ID: {{ $donation->nonprofit->tax_id }}</p>
			<p>Email: <a href="mailto:{{ $donation->nonprofit->email }}" target="_blank">{{ $donation->nonprofit->email }}</a></p>
			<p>Phone: <a href="tel:{{ $donation->nonprofit->phone }}" target="_blank">{{ $donation->nonprofit->phone }}</a></p>
		</section>

		{{-- nonprofit information --}}
		<section class="box information">
			<p><strong>About {{ $donation->nonprofit->name }}</strong></p>
			<p>{!! text_format($donation->nonprofit->description) !!}</p>
		</section>

		{{-- response form --}}
		<section>
			<form
				action="{{ route('api.forprofits.donations.update', [$authForprofit->id, $donation->id]) }}"
				class="response-form"
				@submit.prevent="submitAjaxForm">
				{{ method_field('put') }}
				@if($donation->fulfilled)
					<input type="hidden" name="fulfilled" value="0">
					<button type="submit" class="btn btn--warning btn--large">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Mark as pending
					</button>
				@else
					<input type="hidden" name="fulfilled" value="1">
					<button type="submit" class="btn btn--primary btn--large">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Mark as fulfilled
					</button>
				@endif
			</form>
		</section>
	</div>
@stop