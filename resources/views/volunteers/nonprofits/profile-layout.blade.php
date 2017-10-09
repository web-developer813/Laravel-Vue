@extends('volunteers.layout')

@section('page_id', 'nonprofits-show')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
	{!! php_to_js('stripeKey', getenv('STRIPE_KEY')) !!}
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript">
		Stripe.setPublishableKey(window.stripeKey);
	</script>
@stop

@section('content')
	<div id="profile">
		<div class="profile-content-bg">
			<div class="single-column">

				{{-- profile information --}}
				<div class="profile__information">

					{{-- header --}}
					<div class="profile__header">
						@include('app.components.profile-photo', [
							'source' => $nonprofit->profilePhoto, 'name' => $nonprofit->name
						])
						<div class="profile-header-content">
							<h1 class="profile__name">{{ $nonprofit->name }}</h1>
							<h2 class="profile__mission">{{ $nonprofit->present()->mission }}</h2>
							<div class="profile__location">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
								{{ $nonprofit->present()->location }}
								@if($nonprofit->website_url)
									&middot; <a href="{{ $nonprofit->website_url }}" target="_blank" rel="nofollow" class="profile__website">{{ $nonprofit->formatted_website_url }}</a>
								@endif
							</div>
						</div>
					</div>
					{{-- buttons and stats --}}

						<div class="profile-actions clearfix">
							<div class="donate pull-left">
								<button
									class="btn btn--primary btn--large"
									data-toggle="modal"
									data-target="#donate-modal"
									>Donate Points</button>
							</div>
						</div>

					{{-- description --}}
					@if($nonprofit->description)
						<div class="profile__description">{!! $nonprofit->present()->description !!}</div>
					@endif
					{{-- categories --}}
					@if(count($nonprofit->categories))
						<div class="profile__categories">
							@include('app.components.categories-list', ['categories' => $nonprofit->categories])
						</div>
					@endif
				</div>
			</div>
		</div>

		<div class="single-column">

			{{-- profile tabs --}}
			<ul class="profile-tabs">
				<li><a href="{{ route('nonprofits.show', $nonprofit->id) }}"
					class="{{ nav_active('nonprofits.show') }}">Volunteer with us</a></li>
				<li><a href="{{ route('nonprofits.show.donations', $nonprofit->id) }}"
					class="{{ nav_active('nonprofits.show.donations') }}">Latest Donations</a></li>
				<li><a href="{{ route('nonprofits.show.about', $nonprofit->id) }}"
					class="{{ nav_active('nonprofits.show.about') }}">Get In Touch</a></li>
			</ul>

			@yield('profile-content')

		</div>
	</div>
@stop

@section('templates')

	<div id="donate-modal" class="modal" tabindex="-1" role="dialog">
		<stripe-form submit-url="{{ route('api.volunteers.donations.store', $nonprofit->id) }}" inline-template>
			<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route('api.volunteers.donations.store', $nonprofit->id) }}" method="post"
					@submit.prevent="submit"
					class="js-stripe-form">
						{{ csrf_field() }}
					<div class="modal-header">
						<h1 class="modal-title">You have {{ $authVolunteer->present()->points }} {{ str_plural('point', $authVolunteer->points) }}</h1>
					</div>
					<div class="modal-body">
						<a href="">connect to stripe</a>
						<p>or</p>
								@include('app.components.forms._payment-fields')
						{{-- <input name="points" type="text"> --}}
						<label for="ampunt">Amount</label>
						<input step="0.01" name="amount" type="number" class="form__field" v-model="formData.amount">
						{{-- @include('app.components.form-field', [
							'field' => 'points', 'label' => 'Donate ($)',
							'input' => Form::number('amount', null, ['class' => 'form__field','step' => '0.01','v-model'=>'amount'])
						]) --}}
						{{-- @include('app.components.forms._payment_types') --}}
					</div>
					<div class="modal-footer">
							<button type="button" class="btn btn--link" data-dismiss="modal">Cancel</button>
							@include('app.components.forms.submit-button', ['label' => 'Donate'])
					</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
		</stripe-form>
	</div><!-- /.modal -->
@stop
