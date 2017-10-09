<li class="feed-item feed-item--volunteer">
	<a href="{{ route('volunteers.show', $volunteer->id) }}" class="item__content">
		@include('app.components.profile-photo', [
			'source' => $volunteer->profilePhoto, 'name' => $volunteer->name, 'class' => 'profilePhoto--profile'
		])
		<span class="item__name">{{ $volunteer->name }}</span>
	</a>
</li>