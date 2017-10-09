@foreach($categories as $category)
	<div class="category__checkbox">
		<input
			id="id-checkbox-{{ $category->id }}"
			class="category__input"
			type="checkbox"
			value="{{ $category->id }}"
			name="categories[]"
			v-model="categories" number
		/>
		<label for="id-checkbox-{{ $category->id }}" class="category__label">{{ $category->name }}</label>
	</div>
@endforeach