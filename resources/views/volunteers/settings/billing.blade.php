@extends('volunteers.layout')

@section('page_id', 'settings')

@section('scripts')
	@parent
	{!! php_to_js('stripeKey', getenv('STRIPE_KEY')) !!}
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
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
		@include('volunteers.settings._sidebar')
		<div class="single-column single-column--box">
			{{-- current plan --}}
			<section>
				<h3>Subscription Plan</h3>
				{{-- trial --}}
				@if($authVolunteer->subscribed('main'))
					{{-- on grace period --}}
					@if($authVolunteer->subscription('main')->onGracePeriod())
						<p>Your subscription to the <strong>{{ trans('plans.' . $authVolunteer->subscription('main')->stripe_plan) }} plan</strong> has been cancelled and will end on <strong>{{ $authVolunteer->subscription('main')->ends_at->toFormattedDateString() }}.</strong></p>
						<p>
							<button class="btn btn--primary" data-toggle="modal" data-target="#update-subscription-modal">Resume Payment Plan</button>
						</p>
					{{-- subscribed but no card on file --}}
					@elseif(!$authVolunteer->hasCardOnFile())
						<p>You are currently subscribed to the <strong>{{ trans('plans.' . $authVolunteer->subscription('main')->stripe_plan) }} plan</strong>.</p>
						<p><button class="btn btn--default btn--small" data-toggle="modal" data-target="#new-subscription-modal">Change Payment Plan</button></p>
					{{-- active subscription --}}
					@else
						<p>You are currently subscribed to the <strong>{{ trans('plans.' . $authVolunteer->subscription('main')->stripe_plan) }} plan</strong>.</p>
						<p>
							<button class="btn btn--default btn--small" data-toggle="modal" data-target="#update-subscription-modal">Change Payment Plan</button>
							<button data-url="{{ route('api.volunteers.subscriptions.destroy') }}" class="btn btn--default btn--small" data-method="delete" @click.prevent="ajaxLink">
								<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
								Cancel Plan
							</button>
						</p>
					@endif
				@elseif($authVolunteer->onTrial())
					<section>
						<p>You are on a <strong>free trial</strong> that will expires on <strong>{{ $authVolunteer->trial_ends_at->toFormattedDateString() }}</strong>.</p>
						<p>Upgrading your plan allows you to earn and spend reward points.</p>
						<p><strong>Volunteering is always free.</strong></p>
						<p><button class="btn btn--primary" data-toggle="modal" data-target="#new-subscription-modal">Upgrade Your Plan</button></p>
				@else
					<p>You are not currently on any paid plans.</p>
					<p>Upgrading your plan allows you to earn and spend reward points.</p>
					<p><strong>Volunteering is always free.</strong></p>
					<p><button class="btn btn--primary" data-toggle="modal" data-target="#new-subscription-modal">Upgrade Your Plan</button></p>
				@endif
			</section>

			{{-- payment information  --}}
			@if($authVolunteer->hasStripeId())
			<section>
				<h3>Payment Information</h3>
					<p>You are making payments on your <strong>{{ $authVolunteer->card_brand }}</strong>  ending in <strong>{{ $authVolunteer->card_last_four }}.</strong></p>
					<p><button class="btn btn--default btn--small" data-toggle="modal" data-target="#update-payment-modal">Change Payment Details</button></p>
				<section>
					
				</section>
			</section>
			@endif
			
			@if($authVolunteer->hasStripeId())
			<section>
				<h3>Receipts</h3>
				<p>Get downloadable receipts for all of the payments you made for your Tecdonor account.</p>
				<p><a href="{{ route('volunteers.settings.receipts') }}" class="btn btn--default btn--small">View Payment Receipts</a></p>
			</section>
			@endif
		</div>
	</div>
@stop
@section('templates')
	@if($authVolunteer->subscribed('main') && $authVolunteer->hasCardOnFile())
		@include('volunteers.settings.billing.update-subscription-modal', [
			'vmodel' => false, 'default_plan' => $authVolunteer->subscription('main')->stripe_plan])
	@else
		@include('volunteers.settings.billing.new-subscription-modal', ['vmodel' => true, 'default_plan' => null])
	@endif
	@if($authVolunteer->hasStripeId())
		@include('volunteers.settings.billing.update-payment-modal')
	@endif
@stop