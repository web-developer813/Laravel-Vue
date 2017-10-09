<div id="update-subscription-modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form
				action="{{ route('api.volunteers.subscriptions.update')}}"
				method="put"
				@submit.prevent="submitAjaxForm"
				class="js-stripe-form">

				{{ method_field('put') }}
				
				{{-- header --}}
				<div class="modal-header">
					<h1 class="modal-title">Change Your Plan</h1>
				</div>
				
				{{-- body --}}
				<div class="modal-body">

					{{-- intro --}}
					<section>
						<p>Choose the payment plan that works best for you.</p>
					</section>

					{{-- select plan --}}
					<section>
						@include('volunteers.settings.billing._select-plan')
					</section>

				</div>

				{{-- footer --}}
				<div class="modal-footer">
					<button type="button" class="btn btn--link" data-dismiss="modal" :disabled="submitting">Cancel</button>
					@include('app.components.forms.submit-button', ['label' => 'Update Plan'])
				</div>

			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->