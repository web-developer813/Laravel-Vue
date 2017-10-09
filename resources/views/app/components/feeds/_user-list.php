<li class="list-group-item">
	<div class="media">
		<div class="pr-20">
			<div class="avatar avatar-online">
				<a href="{{ profile }}{{ item.id }}">
					<img v-bind:src="item.profilePhoto" alt="{{ item.name }}">
					<i class="avatar avatar-busy"></i>
				</a>
			</div>
		</div>
		<div class="media-body">
			<h5 class="mt-0 mb-5">
				{{ item.name }}
			</h5>
			<p>
				<i class="icon icon-color md-pin" aria-hidden="true"></i> {{ item.location }}
			</p>
		</div>
		<div v-if="item.following" class="pl-20 align-self-center">
			<form action="{{action}}/{{ item.follow_id }}" method="POST">
				<input type="hidden" name="_token" value="{{token}}">
				<input type="hidden" name="_method" value="DELETE">
				<input type="hidden" name="follow_id" value="{{ item.follow_id }}">
				<button type="submit" id="delete-follow-{{ item.follow_id }}" class="btn btn-success btn-sm">
					Following <i class="icon md-check" aria-hidden="true"></i>
				</button>
			</form>
		</div>
		<div v-else class="pl-20 align-self-center">
			<form action="{{action}}" method="POST">
				<input type="hidden" name="_token" value="{{token}}">
				<input type="hidden" name="_method" value="POST">
				<input type="hidden" name="follow_id" value="{{ item.id }}">
				<button type="submit" id="follow-user-{{ item.id }}" class="btn btn-primary btn-sm">
					Follow <i class="icon md-account-add"></i>
				</button>
			</form>
		</div>
	</div>
</li>