<aside class="sidebar">

	<section class="sidebar__section">
		<h3 class="sidebar__title">Budget Spent This Month</h3>
		<div class="sidebar__points">{{ number_format($authForprofit->monthly_points_spent) }} {{ str_plural('point', $authForprofit->monthly_points_remaining)}}</div>
	</section>
	
	<form
		action="{{ route('api.forprofits.settings.monthly-points', $authForprofit->id) }}"
		method="post"
		@submit.prevent="submitAjaxForm"
		class="sidebar__section">

		{{ csrf_field() }}
		{{ method_field('put') }}

		<section class="sidebar__section">
			<h3 class="sidebar__title">Monthly Points Budget</h3>
				@include('app.components.form-field', [
					'field' => 'monthly_points', 'label' => 'Points',
					'input' => Form::number('monthly_points', old('monthly_points') ?: $authForprofit->monthly_points, ['class' => 'form__field'])
				])
		</section>

		<div class="sidebar__section">
			<button type="submit" class="btn btn--primary btn--large btn--block" :disabled="submitting" v-bind:class="{'btn--loading': submitting}">
				<i class="fa fa-spinner btn__loader" aria-hidden="true" v-bind:class=""></i>
				Save
			</button>
		</div>

	</form>

</aside>