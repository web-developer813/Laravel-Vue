@extends('volunteers.layout')

@section('page_id', 'connections')

@section('body_class','animsition page-user page-aside-static page-aside-right')

@section('page-aside')
	@include('volunteers.dashboard._sidebar')
@stop

@section('content')
	<div class="panel">
		<div class="panel-body">
			<connections
				resource-url="{{ secure_url(route('api.volunteers.volunteers','',false)) }}"
				csrf-token="{{ csrf_token() }}"
				no-results="There are currently no users you are connected to."
				follow-url="{{ secure_url('api/follows') }}" inline-template>
				<div id="vue-wrapper">
					<form class="page-search-form" role="search">
						<div class="input-search input-search-dark">
							<i class="input-search-icon md-search" aria-hidden="true"></i>
							<input v-model="filters.search" type="text" class="form-control" id="inputSearch" name="search" placeholder="Search Users">
							<button type="button" class="input-search-close icon md-close" aria-label="Close"></button>
						</div>
					</form>
					<div class="nav-tabs-horizontal nav-tabs-animate">
						<div class="dropdown page-user-sortlist">
				            Order By: <a class="dropdown-toggle inline-block" data-toggle="dropdown" href="#" aria-expanded="false">Last Active</a>
				            <div class="dropdown-menu animation-scale-up animation-top-right animation-duration-250" role="menu">
								<a class="active dropdown-item" href="javascript:void(0)">Last Active</a>
								<a class="dropdown-item" href="javascript:void(0)">Username</a>
								<a class="dropdown-item" href="javascript:void(0)">Location</a>
								<a class="dropdown-item" href="javascript:void(0)">Registration Date</a>
				            </div>
						</div>
						<ul class="nav nav-tabs nav-tabs-line">
							<tab v-for="tab in tabs" :tab="tab" v-bind:key="tab.id" :active.sync="active" v-on:activate="activateFilter" inline-template>
								<li class="nav-item" role="presentation"><a v-on:click="activate" v-bind:class="active ? 'active nav-link' : 'nav-link'" href="javascript:void(0)">@{{ tab.name }}</a></li>
							</tab>
						</ul>
						<div class="user-content">
							<div class="user-pane animation-fade active">
								<ul class="list-group" v-cloak>
									<li v-for="item in items" class="list-group-item">
										<div class="media">
											<div class="pr-20">
												<div class="avatar avatar-online">
													<a v-bind:href="'volunteers/' + item.volunteer.id">
														<img v-bind:src="item.volunteer.profile_photo" v-bind:alt="item.volunteer.name">
														<i class="avatar avatar-busy"></i>
													</a>
												</div>
											</div>
											<div class="media-body">
												<h5 class="mt-0 mb-5">
													<a v-bind:href="'volunteers/' + item.volunteer.id" class="grey-800">
														@{{ item.volunteer.name }}
													</a>
												</h5>
												<p>
													<i class="icon icon-color md-pin" aria-hidden="true"></i> @{{ item.volunteer.location }}
												</p>
											</div>
											<div class="pl-20 align-self-center">
												<button v-on:click.stop.prevent="handleFollow(item,{{ auth()->user()->id }})" type="button" v-bind:class="item.following ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm'">
													Follow@{{ item.following ? 'ing' : '' }} <i v-bind:class="item.following ? 'icon md-check' : 'icon md-account-add'" aria-hidden="true"></i>
												</button>
											</div>
										</div>
									</li>
								</ul>
								<div class="feed__no-results" v-show="!items.length && !loading">
									@{{ noResults }}
								</div>
								<div class="text-center" v-show="loading">
									@include('app.components.loading')
								</div>
								@include('app.components.feeds.load-more')
							</div>
						</div>
					</div>
				</div>
			</connections>
		</div>
	</div>
@stop