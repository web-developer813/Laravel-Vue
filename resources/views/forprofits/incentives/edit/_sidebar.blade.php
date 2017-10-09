{{--<aside class="sidebar">--}}
	{{--<ul class="sidebar__menu">--}}
		{{--<li class="menu__item">--}}
			{{--<a href="#description"--}}
				{{--@click.prevent="switchToTab('description')"--}}
				{{--v-bind:class="{ 'active': tab == 'description'}">--}}
				{{--Description</a></li>--}}
		{{--<li class="menu__item">--}}
			{{--<a href="#details"--}}
				{{--@click.prevent="switchToTab('details')"--}}
				{{--v-bind:class="{ 'active': tab == 'details'}">Details</a></li>--}}
	{{--</ul>--}}
	{{--<div class="sidebar__section">--}}
		{{--@include('app.components.checkbox-toggle', [--}}
			{{--'field' => 'active', 'label' => 'Active', 'value' => true, 'v_model' => 'fields.active', 'disabled' => !$authForprofit->verified--}}
		{{--])--}}
		{{--@if(!$authForprofit->verified)--}}
			{{--<span class="help-block">Your business is currently pending verification. You can't activate an incentive until your business has been verified.</span>--}}
		{{--@endif--}}
	{{--</div>--}}
	{{--<div class="sidebar__section">--}}
		{{--<button type="submit" class="btn btn--primary btn--large btn--block" :disabled="submitting" v-bind:class="{'btn--loading': submitting}">--}}
			{{--<i class="fa fa-spinner btn__loader" aria-hidden="true" v-bind:class=""></i>--}}
			{{--Save--}}
		{{--</button>--}}
	{{--</div>--}}
{{--</aside>--}}

@if(!$authForprofit->verified)
	<span class="help-block">Your business is currently pending verification. You can't activate an incentive until your business has been verified.</span>
@else
	<label class="form-control-label font-size-18" for="oppublished">Active</label>
	<input type="checkbox" id="active" name="active" data-plugin="switchery" data-size="large" data-color="#4caf50" v-model="fields.active" >
@endif