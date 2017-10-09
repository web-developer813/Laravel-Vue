@extends('volunteers.layout')

@section('page_id', 'incentives-show')

@section('content')
	<div class="single-column single-column--wide">
		<div class="incentive__header">
			{{-- title --}}
			<h1 class="incentive__title">{{ $incentive->title }}</h1>

			{{-- meta --}}
			<div class="incentive__meta">
				Offered by <a href="{{ route('forprofits.show', $incentive->forprofit_id) }}">{{ $incentive->forprofit->name}}</a> &middot; {{ $incentive->present()->date }}
			</div>
		</div>
	</div>
	<div class="two-columns">

		{{-- main content --}}
		<div class="single-column">

			@if($incentive->has_image)
				<div class="incentive__image" style="background-image: url({{ $incentive->image }});"></div>
			@endif

			<div class="incentive__content">
				description
				<div class="incentive__description">{!! $incentive->present()->description !!}</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h4>Available</h4>
					<p>{!! $incentive->availablePurchase() !!} {!! $incentive->tag !!}</p>
				</div>
			</div>
		</div>

		<aside class="sidebar sidebar--right">
			@if(current_mode('volunteer'))
				<section class="incentive__buy">
					<button
							class="btn btn--primary btn--large btn--block"
							data-toggle="modal"
							data-target="#buy-incentive-modal"
							{{ ($authVolunteer->points < $incentive->price) ? 'disabled' : '' || (($incentive->case !="" && $incentive->availablePurchase() == '0') ? 'disabled' : '')}}>
						Buy for <strong>{{ number_format($incentive->price) }} {{ str_plural('point', $incentive->price) }}</strong></button>
					<span class="current-points">You currently have {{ $authVolunteer->points }} {{ str_plural('point', $authVolunteer->points) }}</span>
				</section>
			@endif
			@if($incentive->summary)
				<section>
					<h2>The Gist</h2>
					<p>{{ $incentive->summary }}</p>
				</section>
			@endif
			@if($incentive->quantity)
				<section>
					<h2>Limited Quantity Available</h2>
				</section>
			@endif
			@if($incentive->termsWithExpiration)
				<section class="incentive__terms">
					<h2>Terms & Conditions</h2>
					<p>{{ $incentive->termsWithExpiration}}</p>
				</section>
			@endif
		</aside>

	</div>
@stop

@section('templates')
	<div id="buy-incentive-modal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title">{{ $incentive->title }}</h1>
				</div>
				<div class="modal-body">
					<p>{{ $incentive->summary }}</p>
					<div class="price">
						<strong>{{ number_format($incentive->price) }} {{ str_plural('point', $incentive->price) }}</strong> will be deducted from your account.
					</div>
				</div>
				<div class="modal-footer">
					<form action="{{ route('api.volunteers.incentives-purchases.store', $incentive->id) }}" method="post" @submit.prevent="submitAjaxForm">
						{{ csrf_field() }}
						<button type="button" class="btn btn--link" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn--primary">
							<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
							Confirm</button>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
@stop