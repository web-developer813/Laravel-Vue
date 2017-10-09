 <section>
	@include('app.components.form-field', [
		'field' => 'title', 'label' => 'Title', 'v_model' => 'title',
		'input' => Form::text('title', old('title'), ['class' => 'form__field', 'v-model' => 'fields.title'])
	])
	@include('app.components.form-field', [
		'field' => 'description', 'label' => 'Description', 'v_model' => 'description',
		'input' => Form::textarea('description', old('description'), ['class' => 'form__field', 'rows' => 3, 'v-model' => 'fields.description'])
	])
	<div class="form-field" v-bind:class="{ 'has-error': errors.photo }">
		<label class="form__label">Photo</label>
		<div class="field-wrapper">
			<div class="image-upload js-image-uploader" data-name="photo" style=""></div>
			<span class="error-block" v-if="errors.photo">
				@{{ errors.photo[0] }}
			</span>
		</div>
	</div>
</section>

<section>
	<h3>Categories</h3>
	<div class="categories">
		@foreach($categories as $category)
			<div class="category-checkbox">
				<input
					type="checkbox"
					name="categories[]"
					value="{{ $category->id }}"
					id="id-checkbox-{{ $category->id }}"
					v-model="fields.categories">
				<label for="id-checkbox-{{ $category->id }}" @click.prevent="toggleCategory('{{ $category->id }}')">
					{{ $category->name }}
				</label>
			</div>
		@endforeach
	</div>
</section>

<section>
	<a href="#next" class="btn btn--default btn--block" @click.prevent="switchToTab('details')">Continue to details</a>
</section>