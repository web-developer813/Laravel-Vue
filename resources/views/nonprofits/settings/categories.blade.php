@extends('nonprofits.layout')

@section('page_id', 'settings')

@section('scripts')
	@parent
@stop

@section('content')
	<div class="two-columns">
		@include('nonprofits.settings._sidebar')
		<div class="single-column single-column--box">
			<nonprofit-settings inline-template>
				{{ Form::open([
					'route' => ['nonprofits.settings.categories', $authNonprofit->id], 'method' => 'put', 'files' => true,
					'autocomplete' => 'off', 'novalidate', 'class' => 'form--list js-form'
				]) }}
					<div class="categories">
						@foreach($categories as $category)
							<div class="category-checkbox">
								<input
									type="checkbox"
									name="categories[]"
									value="{{ $category->id }}"
									@if($authNonprofit->categories->contains('id', $category->id))
										checked="checked"
									@endif
									id="id-checkbox-{{ $category->id }}"
									v-model="formData.categories">
								<label for="id-checkbox-{{ $category->id }}" @click.prevent="toggleCategory('{{ $category->id }}')">
									{{ $category->name }}
								</label>
							</div>
						@endforeach
					</div>
					<div class="form__footer">
						<button type="submit" class="btn btn--primary btn--large btn--block">
							<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
							Save Settings
						</button>
					</div>
				{{ Form::close() }}
			</nonprofit-settings>
		</div>
	</div>
@stop