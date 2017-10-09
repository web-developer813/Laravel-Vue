@if($categories->count())
	@foreach($categories as $category)
	<span class="badge badge-primary badge-outline m-5">{{ $category->name }}</span>
	@endforeach
@endif