
	@include('app.components.form-field', [
		'field' => 'title', 'label' => 'Title', 'v_model' => 'title',
		'input' => Form::text('title', $opportunity->title, ['class' => 'form__field', 'v-model' => 'title'])
	])
	@include('app.components.form-field', [
		'field' => 'description', 'label' => 'Description', 'v_model' => 'description',
		'input' => Form::textarea('description', $opportunity->description, ['class' => 'form__field', 'rows' => 3, 'v-model' => 'description'])
	])
	<div class="form-field" v-bind:class="{ 'has-error': errors.photo }">
		<label class="form__label">Photo</label>
		<div class="field-wrapper">
			<div class="image-upload js-image-uploader" data-name="photo" style="background-image:url({{ $opportunity->image }})"></div>
			<span class="error-block" v-if="errors.photo">
				@{{ errors.photo[0] }}
			</span>
		</div>
	</div>

	<h3>Categories</h3>
	<div class="categories">
		@foreach($categories as $category)
			<div class="category-checkbox">
				<input
					type="checkbox"
					name="categories[]"
					value="{{ $category->id }}"
					@if($opportunity->categories->contains('id', $category->id))
						checked="checked"
					@endif
					id="id-checkbox-{{ $category->id }}"
					v-model="categories">
				<label for="id-checkbox-{{ $category->id }}" @click.prevent="toggleCategory('{{ $category->id }}')">
					{{ $category->name }}
				</label>
			</div>
		@endforeach
	</div>
