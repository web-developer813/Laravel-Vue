<li class="list-item">
	{{-- name --}}
	<div class="column column--grow-1 column--big">
		<strong>@{{ item.invitation.email }}</strong>
		<br>
		<small>Invited on @{{ item.invitation.created_at | datestring }}</small>
	</div>
	{{-- resend --}}
	<div class="column column--20">
		<button
			:data-update-url="item.invitation.send_url"
			class="btn btn--small btn--primary btn--block"
			@click.prevent="updateItem">Re-Send</button>
	</div>
	{{-- revoke --}}
	<div class="column column--20">
		<button
			:data-delete-url="item.invitation.delete_url"
			data-item-type="invitation"
			class="btn btn--small btn--warning btn--block"
			@click.prevent="deleteItem(item.invitation.id, $event)">Revoke</button>
	</div>
</li>