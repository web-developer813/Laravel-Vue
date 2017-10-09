@extends('volunteers.layout')

@section('page_id', 'nonprofits-index')

@section('body_class','animsition page-user page-aside-static page-aside-right')

@section('page-aside')
	@include('volunteers.nonprofits._sidebar')
@stop

@section('content')
<nonprofits-feed
	resource-url="{{ secure_url('api/nonprofits') }}"
	location="{{ $authVolunteer->location }}"
	inline-template>

<div class="panel">
	<div class="panel-body">
		<form class="page-search-form" role="search">
			<div class="input-search input-search-dark">
				<i class="input-search-icon md-search" aria-hidden="true"></i>
				<input v-model="search" type="text" class="form-control" id="inputSearch" name="search" placeholder="Search Users">
				<button type="button" class="input-search-close icon md-close" aria-label="Close"></button>
			</div>
		</form>
		<div class="nav-tabs-horizontal nav-tabs-animate" v-cloak>
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
				<template v-for="tab in tabs" inline-template>
					<li class="nav-item" v-bind:key="tab.id" role="presentation"><a v-on:click="this.active = tab" class="nav-link" :tab="tab" href="javascript:void(0)">@{{ tab.name }}</a></li>
				</template>
			</ul>
			<div class="user-content">
				<div class="user-pane animation-fade active">
					<ul class="list-group">
						<li v-for="item in items" class="list-group-item">
							<div class="media">
								<div class="pr-20">
									<div class="avatar">
										<a v-bind:href="item.nonprofit.url">
											<img v-bind:src="item.nonprofit.profile_photo" v-bind:alt="item.nonprofit.name">
										</a>
									</div>
								</div>
								<div class="media-body">
									<h5 class="mt-0 mb-5">
										<a v-bind:href="item.nonprofit.url" class="grey-800">
											@{{ item.nonprofit.name }}
										</a>
									</h5>
									<p>
										<i class="icon icon-color md-pin" aria-hidden="true"></i> @{{ item.nonprofit.full_address }}
									</p>
								</div>
								<div class="pl-20 align-self-center">
									<follow-button 
										follow-url="{{ secure_url('api/follows') }}"
										:item="item"
										:following="item.following"
										:follow-id="item.following ? item.following.id : 0"
										:followable-id="item.following ? item.following.followable_id : 0"
										:object="item.nonprofit.id"
										model="App\Nonprofit">
									</follow-button>
								</div>
							</div>
						</li>
					</ul>
					<div class="text-center" v-show="!items.length && !loading">
						<h1 class="grey-800">There are currently no organizations that match your criteria. Please try adjusting some filters.</h1>
					</div>
					<div class="text-center" v-show="loading">
						@include('app.components.loading')
					</div>
					@include('app.components.feeds.load-more')
				</div>
			</div>
		</div>
	</div>
</div>
</nonprofits-feed>
@stop