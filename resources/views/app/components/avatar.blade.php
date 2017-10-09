<span class="avatar {{ $class or '' }}">
	@if($source)
		<img src="{{ $source }}" alt="{{ $name }}">
	@else
		<div class="avatar__default"><span>{{ strtoupper($name[0]) }}</span></div>
	@endif
</span>
