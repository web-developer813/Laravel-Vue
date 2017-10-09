<stripe-form submit-url="{{ route('api.volunteers.subscriptions.store') }}" inline-template>
<div id="new-subscription-modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form
				action="{{ route('api.volunteers.subscriptions.store')}}"
				method="post"
				@submit.prevent="submit"
				class="js-stripe-form">
				
				{{-- header --}}
				<div class="modal-header">
					<h1 class="modal-title">Upgrade Your Plan</h1>
				</div>
				
				{{-- body --}}
				<div class="modal-body">

					{{-- intro --}}
					<section>
						<p>Choose the payment plan that works best for you, enter your payment information and upgrade your subscription!</p>
					</section>

					{{-- select plan --}}
					<section>
						@include('volunteers.settings.billing._select-plan', ['model' => 'formData.plan_id'])
					</section>

					{{-- payment fields --}}
					<section>
						@include('app.components.forms._payment-fields')
					</section>

				</div>

				{{-- footer --}}
				<div class="modal-footer">
					<button type="button" class="btn btn--link" data-dismiss="modal" :disabled="submitting">Cancel</button>
					@include('app.components.forms.submit-button', ['label' => 'Subscribe'])
				</div>

			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</stripe-form>