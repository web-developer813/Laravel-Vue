@extends('nonprofits.layout')

@section('page_id', 'settings')

@section('scripts')
	@parent
	{!! php_to_js('stripeKey', getenv('STRIPE_KEY')) !!}
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript" src="/js/libs/jquery.payment.min.js"></script>
	<script type="text/javascript">
		Stripe.setPublishableKey(window.stripeKey);
	</script>
@stop

@section('page-header')
	<div class="page-header">
		<h2>Billing Settings</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('nonprofits.settings._sidebar')
		<div class="single-column single-column--box">
			{{-- current plan --}}
			<section>
				<h3>Subscription Plan</h3>
				{{-- trial --}}
				@if($authNonprofit->subscribed('main'))
					{{-- on grace period --}}
					@if($authNonprofit->subscription('main')->onGracePeriod())
						<p>Your subscription to the <strong>{{ trans('plans.' . $authNonprofit->subscription('main')->stripe_plan) }} plan</strong> has been cancelled and will end on <strong>{{ $authNonprofit->subscription('main')->ends_at->toFormattedDateString() }}.</strong></p>
						<p>
							<button class="btn btn--primary" data-toggle="modal" data-target="#update-subscription-modal">Resume Payment Plan</button>
						</p>
					{{-- subscribed but no card on file --}}
					@elseif(!$authNonprofit->hasCardOnFile())
						<p>You are currently subscribed to the <strong>{{ trans('plans.' . $authNonprofit->subscription('main')->stripe_plan) }} plan</strong>.</p>
						<p><button class="btn btn--default btn--small" data-toggle="modal" data-target="#new-subscription-modal">Change Payment Plan</button></p>
					{{-- active subscription --}}
					@else
						<p>You are currently subscribed to the <strong>{{ trans('plans.' . $authNonprofit->subscription('main')->stripe_plan) }} plan</strong>.</p>
						<p>
							<button class="btn btn--default btn--small" data-toggle="modal" data-target="#update-subscription-modal">Change Payment Plan</button>
							<button data-url="{{ route('api.nonprofits.subscriptions.destroy') }}" class="btn btn--default btn--small" data-method="delete" @click.prevent="ajaxLink">
								<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
								Cancel Plan
							</button>
						</p>
					@endif
				@elseif($authNonprofit->onTrial())
					<section>
						<p>You are on a <strong>free trial</strong> that will expires on <strong>{{ $authNonprofit->trial_ends_at->toFormattedDateString() }}</strong>.</p>
						<p>Upgrading your plan allows you to receive donations and spend reward points with our corporate partners.</p>
						<p><strong>Creating opportunities is always free.</strong></p>
						<p><button class="btn btn--primary" data-toggle="modal" data-target="#new-subscription-modal">Upgrade Your Plan</button></p>
				@else
					<p>You are not currently on any paid plans.</p>
					<p>Upgrading your plan allows you to receive donations and spend reward points with our corporate partners.</p>
					<p><strong>Creating opportunities is always free.</strong></p>
					<p><button class="btn btn--primary" data-toggle="modal" data-target="#new-subscription-modal">Upgrade Your Plan</button></p>
				@endif
			</section>

			{{-- payment information  --}}
			@if($authNonprofit->hasStripeId())
			<section>
				<h3>Payment Information</h3>
					<p>You are making payments on your <strong>{{ $authNonprofit->card_brand }}</strong>  ending in <strong>{{ $authNonprofit->card_last_four }}.</strong></p>
					<p><button class="btn btn--default btn--small" data-toggle="modal" data-target="#update-payment-modal">Change Payment Details</button></p>
				<section>
					
				</section>
			</section>
			@endif
			
			@if($authNonprofit->hasStripeId())
			<section>
				<h3>Receipts</h3>
				<p>Get downloadable receipts for all of the payments you made for your Tecdonor account.</p>
				<p><a href="{{ route('nonprofits.settings.receipts') }}" class="btn btn--default btn--small">View Payment Receipts</a></p>
			</section>
			@endif
		</div>
	</div>
@stop
@section('templates')
	@if($authNonprofit->subscribed('main') && $authNonprofit->hasCardOnFile())
		@include('nonprofits.settings.billing.update-subscription-modal', [
			'vmodel' => false, 'default_plan' => $authNonprofit->subscription('main')->stripe_plan])
	@else
		@include('nonprofits.settings.billing.new-subscription-modal', ['vmodel' => true, 'default_plan' => null])
	@endif
	@if($authNonprofit->hasStripeId())
		@include('nonprofits.settings.billing.update-payment-modal')
	@endif
@stop