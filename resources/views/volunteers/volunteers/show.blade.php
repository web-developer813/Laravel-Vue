@extends('volunteers.layout')

@section('page_id', 'page-profile')

@section('body_class','animsition page-profile-v2')

@section('content')
<div class="row">
	<div class="col-lg-6 col-xl-3">
		<div class="user-info card card-shadow text-center">
			<div class="user-base card-block">
				<a class="avatar img-bordered avatar-100" href="javascript:void(0)">
					<img src="{{ $volunteer->profile_photo }}" alt="{{ $volunteer->name }}">
				</a>
				<h4 class="user-name">{{ $volunteer->name }}</h4>
				<p class="user-location">{{ $volunteer->location }}</p>
				@if($volunteer->description)
                <p>{{ $volunteer->present()->description }}</p>
				@endif
			</div>
			<div class="user-connect mb-20">
			@if($volunteer->id == $authVolunteer->id)
				<a href="{{ route('settings.profile') }}" class="btn btn-primary btn-outline">Edit Profile <i aria-hidden="true" class="icon md-edit"></i></a>
			@else
				<follow-button
					follow-url="{{ secure_url('api/follows') }}"
					:following="{{ $volunteer->following }}"
					:follow-id="{{ $volunteer->follow_id }}"
					:followable-id="{{ $volunteer->followable_id }}"
					:object="{{ $volunteer->id }}"
					btn-unfollow-class="btn btn-default dropdown-toggle" inline-template>
					<div class="btn-group" role="group">
						<button v-bind:class="btnClass" @click="handleFollow" data-style="zoom-in" v-if="!following"><span class="ladda-label"> @{{ btnText }}  <i :class="iconClass" aria-hidden="true"></i> </span></button>
						<button v-bind:class="btnClass" data-toggle="dropdown" aria-expanded="false" v-if="following">
							<i :class="iconClass" aria-hidden="true"></i>
							@{{ btnText }}
						</button>
						<div class="dropdown-menu" role="menu" v-if="following">
							<a class="dropdown-item" @click="handleFollow" href="javascript:void(0)" role="menuitem">Unfollow {{ $volunteer->firstname }}</a>
						</div>
					</div>

				</follow-button>
				<connect-button
					connect-url="{{ secure_url(URL::route('connections.store','',false)) }}"
					:connected="{{ $volunteer->connected }}"
					:connected-status="{{ $volunteer->connected ? $volunteer->connected->status : -1 }}"
					:target-id="{{ $volunteer->id }}"
					btn-disconnect-class="btn btn-default dropdown-toggle" inline-template>
					<div class="btn-group" role="group">
						<button type="button" v-bind:class="btnClass" data-target="#connectConfirm" data-toggle="modal" v-if="status < 0"><span class="ladda-label">@{{ btnText }} <i :class="iconClass" aria-hidden="true"></i></span></button>
						<button type="button" v-bind:class="btnClass" data-toggle="dropdown" aria-expanded="false" v-if="status >= 0">
							<i class="icon md-leak" aria-hidden="true"></i>
							@{{ btnText }}
						</button>
						<div class="dropdown-menu" role="menu" v-if="status >= 0">
							<a class="dropdown-item" @click="handleConnect" href="javascript:void(0)" role="menuitem">@{{ status == 0 ? 'Cancel Request to' : 'Disconnect from' }} {{ $volunteer->firstname }}</a>
						</div>
						<div class="modal fade modal-fill-in" id="connectConfirm" aria-hidden="false" aria-labelledby="exampleFillIn" role="dialog" tabindex="-1">
							<div class="modal-dialog modal-simple">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
										<h1 class="modal-title" id="connectConfirmModalTitle">Reason for connecting?</h1>
									</div>
									<div class="modal-body text-left">
										<p>Tell {{ $volunteer->firstname }} why you'd like to get connected</p>
										<div class="row mt-10 mb-10 pt-10 pb-10 bg-grey-300">
											<div class="col-sm-5">
												<div class="media">
													<div class="pr-10">
														<span class="avatar avatar-sm img-bordered bg-white">
															<img src="{{ $authVolunteer->profile_photo }}" alt="{{ $authVolunteer->name }}" />
														</span>
													</div>
													<div class="media-body">
														<h6 class="media-heading">{{ $authVolunteer->name }}</h6>
														<div class="media-meta">
															<p>{{ $authVolunteer->location }}</p>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-2 vertical-align-middle">
												<i class="font-size-60 icon icon-lg md-link"></i>
											</div>
											<div class="col-sm-5">
												<div class="media">
													<div class="pr-10">
														<span class="avatar avatar-sm avatar-bordered img-bordered bg-white">
															<img src="{{ $volunteer->profile_photo }}" alt="{{ $volunteer->name }}" />
														</span>
													</div>
													<div class="media-body">
														<h6 class="media-heading">{{ $volunteer->name }}</h6>
														<div class="media-meta">
															<p>{{ $volunteer->location }}</p>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row mt-20">
											<div class="col-xl-6 form-group">
												<template v-for="msg in messages">
												<div class="radio-custom radio-primary">
													<input type="radio" :id="msg.id" name="messages[]" :value="msg.message" v-model="message">
													<label :for="msg.id">@{{ msg.title }}</label>
												</div>
												</template>
											</div>
											<div class="col-xl-6">
												<textarea class="form-control" rows="10">@{{ message }}</textarea>
											</div>
										</div>
										<div class="row mt-20 mb-20">
											<div class="col align-self-end text-right">
												<div class="button-group" role="group">
													<button type="button" class="btn btn-flat btn-default" data-dismiss="modal" aria-label="Close">
														Cancel
													</button>
													<button class="btn btn-success btn-lg" role="button" v-on:click="sendRequest" data-dismiss="modal">Connect</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</connect-button>
			@endif
			</div>
			@if(count($volunteer->categories))
            <div class="user-categories mb-20">
				<h6>{{ properize($volunteer->firstname) }} Interests</h6>
				@include('app.components.categories-list', ['categories' => $volunteer->categories])
            </div>
			@endif
			<div class="card-footer text-center profile-stat-count">
				<div class="row no-space">
					<div class="col-4">
						<strong class="profile-stat-count">{{ $volunteer->follows->count() }}</strong>
						<span>Followers</span>
					</div>
					<div class="col-4">
						<strong class="profile-stat-count">{{ $volunteer->following()->count() }}</strong>
						<span>Following</span>
					</div>
					<div class="col-4">
						<strong class="profile-stat-count">{{ count($volunteer->connections) }}</strong>
						<span>Connections</span>
					</div>
				</div>
			</div>
		</div>
		<div class="user-friends card card-shadow">
			<div class="card-block">
				<h4 class="card-title mb-20">
					{{ properize($volunteer->firstname) }} Connections
				</h4>
				@if (count($volunteer->connections))
				<ul class="list-group list-group-full m-0">
					@foreach($volunteer->connections as $connection)
					<li class="list-group-item">
						<div class="media">
							<div class="pr-20">
								@if($connection->status)
								<a class="avatar-{{ $connection->id }} avatar avatar-online" href="{{ $connection->url }}">
								@else
								<a class="avatar-{{ $connection->id }} avatar avatar-off" href="{{ $connection->url }}">
								@endif
									<img class="img-fluid" src="{{ $connection->profile_photo }}" alt="{{ $connection->name }}">
									<i></i>
								</a>
							</div>
							<div class="media-body">
								<h5 class="mt-0 mb-5 hover"><a href="{{ $connection->url }}" class="grey-700">{{ $connection->name }}</a></h5>
								<small>{{ $connection->location }}</small>
							</div>
						</div>
					</li>
					@endforeach
				</ul>
				@else
				<div class="text-center">
					<i class="icon md-accounts font-size-40"></i>
					<h5 class="grey-600">It looks like {{ $volunteer->firstname }} hasn't made any connections yet.</h5>
				</div>
				@endif
			</div>
		</div>
	</div>
	<div class="col-lg-12 col-xl-6">
		<div class="panel panel-bordered">
			<div class="panel-body">
				<skill-endorsement
					@if(current_mode()=='nonprofit')
					logged-account="{{$authNonprofit}}"
					mode="nonprofit"
					@elseif(current_mode() == 'forprofit')
					logged-account="{{$authForprofit}}"
					mode="forprofit"
					@else
					logged-account="{{$authVolunteer}}"
					mode="volunteer"
					@endif
					current-volunteer-id="{{$volunteer->id}}"
					current-volunteer-name="{{$volunteer->name}}"
					resource-url="{{ secure_url(URL::route('api.volunteers.skills', ['volunteer_id' => $volunteer->id])) }}"
					inline-template>
					@include('app.templates.skill-endorsement')
				</skill-endorsement>
			</div>
		</div>
		@if($volunteer->id == $authVolunteer->id)
		<create-post
		action="{{ secure_url('api/posts') }}"
		csrf-token="{{ csrf_token() }}" inline-template>
		@include('app.templates.create-post')
		</create-post>
		@endif
		<simple-feed
			resource-url="{{ secure_url(URL::route('posts.index', ['user_id' => $volunteer->id], false)) }}"
			feed-type="standard" inline-template>
		<div class="vue-wrapper">
			<h4>{{ properize($volunteer->firstname) }} Timeline</h4>
			<div class="user-timeline animation-slide-top animation-delay-300" v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="10" infinite-scroll-immediate-check="false" v-for="item in items" v-bind:key="item">
				@include('app.components.feeds._post-item')
			</div>
			<div class="card card-shadow"  v-if="!loading && !items.length">
				<div class="row justify-content-center">
					<div class="col-md-8 text-center mt-40 mb-40">
						<i class="icon md-time-restore font-size-60"></i>
						<h2 class="grey-600">{{ properize($volunteer->firstname) }} timeline looks a bit empty.</h2>
					</div>
				</div>
			</div>
			<div class="row justify-content-center" v-show="loading">
				<div class="col-md-8 text-center mt-40 mb-40">
					@include('app.components.loading')
				</div>
			</div>
		</div>
		</simple-feed>
	</div>
	<div class="col-lg-6 col-xl-3 ">
		<div class="card user-donations">
			<div class="card-header card-header-transparent p-20">
				<h4 class="card-title mb-0">{{ properize($volunteer->firstname) }} Donations</h4>
			</div>
			<div class="card-block">
			@if(count($volunteer->donations))
				<ul class="timeline timeline-single">
					@foreach($volunteer->donations as $donation)
					<li class="timeline-item">
			            <div class="timeline-dot"></div>
						<div class="timeline-content">
							<div class="media pl-20">
								<div class="media-body">
									<h5 class="mt-0 mb-5 hover">Donated <span class="font-weight-500">{{ $donation['points'] }}</span> points to <a href="{{ $donation['nonprofit']['url'] }}">{{ $donation['nonprofit']->name }}</a></h5>
									<small>{{ $donation['created_at'] }}</small>
								</div>
							</div>
						</div>
					</li>
					@endforeach
				</ul>
			@else
				<div class="margin-left-15 margin-right-15 text-center">
					<i class="icon md-money-off font-size-40"></i>
					<h5 class="grey-600">It looks like {{ $volunteer->firstname }} hasn't made any donations yet.</h5>
				</div>
			@endif
			</div>
			@if(count($volunteer->donations))
			<div class="card-footer text-center">
				<a href="javascript:void(0)" class="grey-700" data-target="#donationsModal" data-toggle="modal">
					<i class="icon md-money-box"></i> View All Donations
				</a>
				<div class="modal fade modal-fade-in-scale-up" id="donationsModal" aria-hidden="true" aria-labelledby="donationsModalTitle" role="dialog" tabindex="-1">
					<div class="modal-dialog modal-simple">
						<donations-feed
							resource-url="{{ secure_url(route('api.volunteers.donations', ['volunteer' => $volunteer->id],false)) }}"
							no-results="This volunteer hasn't made any donations yet."
							inline-template>
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title">{{ properize($volunteer->firstname) }} Donations</h4>
							</div>
							<div class="modal-body" v-cloak>
								<ul class="timeline timeline-simple" v-show="items.length >=1">
									<template v-for="(item, index) in items">
									<li v-bind:class="['timeline-item', index % 2 !== 0 ? 'timeline-reverse' : '']">
										<div class="timeline-dot" v-bind:data-placement="index % 2 === 0 ? 'right' : 'left'" data-toggle="tooltip" data-trigger="hover" v-bind:data-original-title="item.donation.created_at | datestring"></div>
										<div class="timeline-content vertical-align-middle">
											<div v-bind:class="{ 'text-right': index % 2 === 0, 'text-left': index % 2 !== 0 }">
												Donated <span class="font-weight-500">@{{ item.donation.points | number }} points</span> to <a v-bind:href="item.nonprofit.url">@{{ item.nonprofit.name }}</a>
												<a class="avatar margin-left-15" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="June Lane">
													<img v-bind:src="item.nonprofit.profile_photo" v-bind:alt="item.nonprofit.name">
												</a>
											</div>
										</div>
									</li>
									</template>
								</ul>
								<div class="row justify-content-center" v-if="!items.length && !loading">
									<div class="col-md-8 text-center">
										<i class="icon md-money-off font-size-60"></i>
										<h1 class="mt-40 grey-600">It looks like {{ $volunteer->firstname }} hasn't made any donations yet.</h1>
									</div>
								</div>
								<div class="row justify-content-center" v-show="loading">
									<div class="col-md-10 text-center">
										@include('app.components.loading')
									</div>
								</div>
								@include('app.components.feeds.load-more')
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Close</button>
							</div>
						</div>
						</donations-feed>
					</div>
				</div>
			</div>
			@endif
		</div>
		<div class="card user-experience">
			<div class="card-header card-header-transparent p-20">
				<h4 class="card-title mb-0">{{ properize($volunteer->firstname) }} Experience</h4>
			</div>
			<div class="card-block">
				@if(count($volunteer->hours))
				<ul class="timeline timeline-single">
					@foreach($volunteer->hours as $hour)
						<li class="timeline-item">
				            <div class="timeline-dot"></div>
							<div class="timeline-content">
								<div class="media pl-20">
									<div class="media-body">
										<h5 class="mt-0 mb-5 hover">Volunteered for <span class="font-weight-500">{{ $hour['minutes'] }}</span></h5>
										<p class="mb-0">{{ $hour['opportunity']['title'] }} &middot; <a href="{{ $hour['nonprofit']['url'] }}">{{ $hour['nonprofit']['name'] }}</a></p>
										<small>{{ $hour['start_date'] }}
										@if($hour['has_multiple_dates'])
										<span> to {{ $hour['end_date'] }}</span>
										@endif
										</small>
									</div>
								</div>
							</div>
						</li>
					@endforeach
				</ul>
				@else
				<div class="margin-left-15 margin-right-15 text-center">
					<i class="icon md-calendar-note font-size-40"></i>
					<h5 class="grey-600">It looks like {{ $volunteer->firstname }} hasn't done any volunteer work yet.</h5>
				</div>
				@endif
			</div>
			@if(count($volunteer->hours))
			<div class="card-footer text-center">
				<a href="javascript:void(0)" class="grey-700" data-target="#hoursModal" data-toggle="modal">
					<i class="icon md-money-box"></i> View More Hours
				</a>
				<div class="modal fade modal-fade-in-scale-up" id="hoursModal" aria-hidden="true" aria-labelledby="hoursModalTitle" role="dialog" tabindex="-1">
					<div class="modal-dialog modal-simple">
						<hours-feed
						resource-url="{{ secure_url(URL::route('api.volunteers.hours', ['volunteer' => $volunteer->id], false)) }}"
						no-results="This volunteer has not completed any work yet."
						inline-template>
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title">{{ properize($volunteer->firstname) }} Hours</h4>
							</div>
							<div class="modal-body" v-cloak>
								<div v-for="item in items" class="col-lg-6">
									<div class="card card-shadow card-bordered">
										<div class="card-header card-header-transparent cover overlay" style="height: calc(100% - 100px);">
											<img class="cover-image" v-if="item.opportunity.has_image" v-bind:src="item.opportunity.image_url" v-bind:alt="item.nonprofit.name" style="height: 100%;">
											<div class="overlay-panel overlay-background overlay-top">
												<div class="row">
													<div class="col-6">
														<div class="font-size-20 white">@{{ item.nonprofit.name }}</div>
														<div class="font-size-14 grey-400">@{{ item.nonprofit.mission }}</div>
													</div>
													<div class="col-6 text-right">
														<div class="avatar">
															<img v-bind:src="item.nonprofit.profile_photo" v-bind:alt="item.nonprofit.name">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-block">
											<h4>@{{ item.opportunity.title }}</h4>
											<div class="item__meta">
												<p>
													<strong>Volunteered for @{{ item.hours.minutes | min-to-hours }}</strong>
													&middot; <a v-bind:href="item.nonprofit.url">@{{ item.nonprofit.name }}</a>
												</p>
											</div>
											<div class="item__meta">
												<small>@{{ item.hours.start_date | datestring }}
												<span v-if="item.hours.multiple_dates"> to @{{ item.hours.end_date | datestring }}</span></small>
											</div>
											<div class="item__description" v-if="item.opportunity.excerpt">
												@{{ item.opportunity.excerpt }}
											</div>
											<div class="item__meta mt-20" v-if="item.opportunity.categories.length">
												<span v-for="category in item.opportunity.categories" class="badge badge-primary badge-outline m-5">@{{ category.name }}</span>
											</div>
										</div>
									</div>
								</div>
								<div class="row justify-content-center" v-if="!items.length && !loading">
									<div class="col-md-8 text-center">
										<i class="icon md-calendar-note font-size-60"></i>
										<h1 class="mt-40 grey-600">It looks like {{ $volunteer->firstname }} hasn't made any donations yet.</h1>
									</div>
								</div>
								<div class="row justify-content-center" v-show="loading">
									<div class="col-md-10 text-center">
										@include('app.components.loading')
									</div>
								</div>
								@include('app.components.feeds.load-more')
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Close</button>
							</div>
						</div>
						</hours-feed>
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
@stop
