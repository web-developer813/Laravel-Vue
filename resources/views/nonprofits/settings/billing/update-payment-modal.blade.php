<stripe-form submit-url="{{ route('api.volunteers.update-credit-card') }}" inline-template>
<div id="update-payment-modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form
				action="{{ route('api.volunteers.update-credit-card')}}"
				method="post"
				@submit.prevent="submit"
				class="js-stripe-form">
				
				{{-- header --}}
				<div class="modal-header">
					<h1 class="modal-title">Update Credit Card</h1>
				</div>
				
				{{-- body --}}
				<div class="modal-body">

					{{-- intro --}}
					<section>
						<p>Please enter your new payment information below.</p>
					</section>

					{{-- payment fields --}}
					<section>
						@include('app.components.forms._payment-fields')
					</section>

				</div>

				{{-- footer --}}
				<div class="modal-footer">
					<button type="button" class="btn btn--link" data-dismiss="modal" :disabled="submitting">Cancel</button>
					@include('app.components.forms.submit-button', ['label' => 'Update Credit Card'])
				</div>

			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</stripe-form>