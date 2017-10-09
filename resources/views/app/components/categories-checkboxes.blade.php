@foreach($categories as $category)
<li class="list-inline-item">
	<div class="checkbox-custom checkbox-success">
		<input
			id="id-checkbox-{{ $category->id }}"
			type="checkbox"
			value="{{ $category->id }}"
			name="categories[]"
			@if (is_array(old('categories')))
				{{ in_array($category->id, old('categories')) ? 'checked' : '' }}
			@elseif(isset($entity))
				{{ in_array($category->id, $entity->categories->pluck('id')->toArray()) ? 'checked' : '' }}
			@endif
			@if(isset($v_model))
				v-model="{{ $v_model }}"
			@endif
		/>
		<label for="id-checkbox-{{ $category->id }}">{{ $category->name }}</label>
	</div>
</li>
@endforeach