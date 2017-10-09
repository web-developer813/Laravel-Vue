<aside class="sidebar">
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="#description"
				@click.prevent="switchToTab('description')"
				v-bind:class="{ 'active': tab == 'description'}">
				Description</a></li>
		<li class="menu__item">
			<a href="#details"
				@click.prevent="switchToTab('details')"
				v-bind:class="{ 'active': tab == 'details'}">Details</a></li>
	</ul>
	<div class="sidebar__section">
		@include('app.components.checkbox-toggle', [
			'field' => 'published', 'label' => 'Published', 'value' => true, 'v_model' => 'published', 'disabled' => !$authNonprofit->verified
		])
		@if(!$authNonprofit->verified)
			<span class="help-block">Your organization is currently pending verification. You can't activate an incentive until your organization has been verified.</span>
		@endif
	</div>
	<div class="sidebar__section">
		<button type="submit" class="btn btn--primary btn--large btn--block" :disabled="submitting" v-bind:class="{'btn--loading': submitting}">
			<i class="fa fa-spinner btn__loader" aria-hidden="true" v-bind:class=""></i>
			Save
		</button>
	</div>
</aside>