<div class="card card-shadow card-bordered">
	<div class="card-header cover" v-if="item.object.has_image">
		<img class="cover-image" :src="item.object.image_url" :alt="item.object.title" v-if="item.object.has_image">
	</div>
	<div :class="item.object.has_image ? 'card-block pt-20 pb-5' : 'card-block pt-15 pb-5'">
		<div class="media">
			<div class="media-left">
				<a class="avatar avatar-100 bg-white img-bordered" href="javascript:void(0)">
					<img class="img-responsive" v-bind:src="item.actor.profile_photo" v-bind:alt="item.actor.name">
				</a>
			</div>
			<div class="media-body">
				<h4 class="media-heading mb-0">
					<a v-bind:href="item.actor.url" v-if="item.slug == 'post'">@{{ item.actor.name }}</a>
					<a v-bind:href="item.object.url" v-if="item.slug == 'opportunity'">@{{ item.object.title }}</a>
				</h4>
				<div v-if="item.slug == 'post'">@{{ item.formated_time }}</div>
				<div v-if="item.slug == 'opportunity'">
					<small>Posted by <a v-bind:href="item.actor.url" class="grey-800">@{{ item.actor.name }}</a></small>
				</div>
			</div>
			<div class="media-right">
				<div class="dropdown">
					<a class="card-action" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon md-more-vert" aria-hidden="true"></i></a>
					<div class="dropdown-menu bullet dropdown-menu-right" role="menu">
						<follow-button 
							:item="item"
							follow-url="{{ secure_url('api/follows') }}"
							:following="item.following"
							:follow-id="item.following ? item.following.id : 0"
							:followable-id="item.actor.id"
							:object="item.actor.id"
							unfollow-text="Unfollow"
							icon-unfollow-class="icon md-eye-off" inline-template>
						<a class="dropdown-item" href="javascript:void(0)" @click="handleFollow" role="menuitem" data-style="zoom-in"><span class="ladda-label"><i :class="iconClass" aria-hidden="true"></i> @{{ btnText }} @{{ item.slug == 'opportunity' ? item.actor.name : item.actor.firstname }}</span></a>
						</follow-button>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon md-share" aria-hidden="true"></i> Share this Post</a>
						<a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon md-flag" aria-hidden="true"></i> Flag this Post</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon md-settings" aria-hidden="true"></i> View Full Post</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-block pb-15">
		<div class="pt-10 pb-10">
			<p class="card-text" v-if="item.slug == 'post'">@{{ item.object.content }}</p>
			<p class="card-text" v-if="item.slug == 'opportunity'">@{{ item.object.excerpt }}</p>
			<div v-if="item.slug == 'opportunity'">
				<div class="op-dates">
					<i class="icon md-calendar"></i>
					<span v-if="item.object.has_dates">
						@{{ item.object.start_date | moment 'ddd MMM Do, YYYY' }}
						<span v-if="item.object.has_multiple_dates">&mdash; @{{ item.object.end_date | moment 'ddd MMM Do, YYYY' }}</span>
						<span v-if="item.object.expired">(Expired)</span>
					</span>
					<span v-if="!item.object.has_dates">@{{ item.object.flexible_dates_label }}</span>
				</div>
				<div class="op-location">
					<i class="icon md-map"></i>
					<span v-if="item.object.has_location">@{{ item.object.full_address }}</span>
					<span v-if="!item.object.has_location">Can be done remotely</span>
				</div>
				<div class="op-categories">
					
				</div>
			</div>
		</div>
		<div class="social-actions text-right mt-20">
			<button type="button" class="btn btn-icon btn-info btn-round btn-sm float-left">
				<i class="icon md-star"></i>
			</button>
			<button type="button" class="btn btn-primary btn-sm mr-10">
				<i class="icon md-share"></i>
			</button>
			<like-button 
				:item="item"
				like-url="{{ secure_url(URL::route('likes.store','',false)) }}"
				unlike-url="{{ secure_url(URL::route('likes.destroy','',false)) }}"
				:liked-by-me="item.like"
				:like-id="item.like ? item.like.id : 0"
				:likable-id="item.like ? item.like.likable_id : 0"
				:object="item.object.id"
				:model="item.type">
			</like-button>
		</div>
	</div>
</div>