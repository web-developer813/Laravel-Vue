@if($following)
<form action="{{ secure_url(URL::route('follows.destroy',$followable_id,false)) }}" method="POST">
	{{ csrf_field() }}
	{{ method_field('DELETE') }}
	<input type="hidden" name="followable_id" value="{{ $followable_id }}">
	<button type="submit" id="delete-follow-{{ $followable_id }}" class="btn btn-success btn-sm">
		Following <i class="icon md-check" aria-hidden="true"></i>
	</button>
</form>
@else
<form action="{{ secure_url('follows.store') }}" method="POST">
	{{ csrf_field() }}
	{{ method_field('POST') }}
	<input type="hidden" name="entity_to_follow" value="{{ $volunteer_id }}">
	<input type="hidden" name="entity_type" value="App\Volunteer">
	<button type="submit" id="follow-user-{{ $volunteer_id }}" class="btn btn-primary btn-sm">
		Follow <i class="icon md-account-add"></i>
	</button>
</form>
@endif