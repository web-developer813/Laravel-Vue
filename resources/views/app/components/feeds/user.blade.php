<li class="list-group-item">
	<div class="media">
		<div class="pr-20">
			<div class="avatar avatar-online">
				<img src="{{ $user['volunteer']['profilePhoto'] }}" alt="{{ $user['volunteer']['name'] }}">
				<i class="avatar avatar-busy"></i>
			</div>
		</div>
		<div class="media-body">
			<h5 class="mt-0 mb-5">
				{{ $user['volunteer']['name'] }}
			</h5>
			<p>
				<i class="icon icon-color md-pin" aria-hidden="true"></i> {{ $user['volunteer']['location'] }}
			</p>
		</div>
		<div class="pl-20 align-self-center">
			@if($user['following'])
			<form action="{{url('follow') }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				<input type="hidden" name="follow_id" value="{{ $user['volunteer']['id'] }}">
				<button type="submit" id="follow-user-{{ $user['volunteer']['id'] }}" class="btn btn-primary btn-sm">
					<i class="icon md-check" aria-hidden="true"></i>Following
				</button>
			</form>
			@else
			<form action="{{url('follow') }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('POST') }}
				<input type="hidden" name="follow_id" value="{{ $user['volunteer']['id'] }}">
				<button type="submit" id="follow-user-{{ $user['volunteer']['id'] }}" class="btn btn-primary btn-sm">
					<i class="fa fa-btn fa-user"></i>Follow
				</button>
			</form>
			@endif
		</div>
	</div>
</li>