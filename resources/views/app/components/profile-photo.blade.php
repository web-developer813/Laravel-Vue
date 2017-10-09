@if($source)
	<img class="{{ isset($class) ? $class : '' }}" src="{{ $source }}" alt="{{ $name }}" />
@else
	<div class="profile-photo__default {{ isset($class) ? $class : '' }}"><span>{{ $name[0] }}</span></div>
@endif