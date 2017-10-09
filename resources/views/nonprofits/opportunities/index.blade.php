@extends('nonprofits.layout')

@section('body_class', 'animsition td-tables')

@section('page_id', 'nonprofit-admin-opportunities-index')

@section('page-content')
<simple-feed
	resource-url="{{ secure_url(URL::route('api.nonprofits.opportunities', $authNonprofit->id, false)) }}"
	no-results="There are no opportunities matching your criteria."
	inline-template>
<div class="page">
	<div class="page-main">
		<div class="page-header">
			<h1 class="page-title mb-10">Manage Opportunities</h1>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('nonprofits.dashboard', $authNonprofit->id) }}">Dashboard</a></li>
				<li class="breadcrumb-item active">Manage Opportunities</li>
			</ol>
			<div class="page-header-actions">
				<form class="panel-search-form" role="search">
					<div class="form-group">
						<div class="input-search">
							<i class="input-search-icon md-search" aria-hidden="true"></i>
							<input type="text" class="form-control" name="search" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search...">
							<button type="button" class="input-search-close icon md-close" aria-label="Close" @click="filters.search = ''"></button>
						</div>
					</div>
	            </form>
			</div>
		</div>
		<div class="page-content container-fluid">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">{{ $authNonprofit->name }} Opportunities</h3>
					<div class="panel-actions">
						<a href="{{ route('nonprofits.opportunities.create', $authNonprofit->id) }}" role="button" class="btn btn-success btn-lg waves">
							<span>New Opportunity <i class="icon md-plus-circle-o" aria-hidden="true"></i></span>
						</a>
					</div>
		        </div>
		        <div class="panel-body">
					<table class="table td-opportunities" v-cloak>
						<thead>
							<tr>
								<th>Status</th>
								<th>Details</th>
								<th>Actions</th>
							</tr>
						</thead>
						{{-- list --}}
						<tbody>
							<template v-for="item in items">
								<tr>
									<td class="work-status">
										<span v-if="item.opportunity.expired" class="badge badge-error">Expired</span>
										<span v-if="!item.opportunity.expired && item.opportunity.published" class="badge badge-success">Published</span>
										<span v-if="!item.opportunity.published" class="badge badge-warning">Draft</span>
									</td>
									<td class="subject">
										<div class="table-content">
											<a v-bind:href="item.admin.edit_url" class="font-size-14 font-weight-500">@{{ item.opportunity.title }}</a>
											<br>
											<span class="blue-grey-400">
												<small v-if="item.opportunity.has_dates">@{{ item.opportunity.formatted_dates }}</small>
												<small v-if="!item.opportunity.has_dates">Flexible dates</small>
												<small>/</small>
												<small v-if="item.opportunity.has_location">@{{ item.opportunity.short_location }}</small>
												<small v-if="!item.opportunity.has_location">Remote work</small>
											</span>
										</div>
									</td>
									<td class="actions">
										<a v-bind:href="item.admin.edit_url" class="btn btn-primary btn-outline btn-animate btn-animate-side">
											<span><i class="icon md-edit" aria-hidden="true"></i>Edit</span>
										</a>
										<a v-bind:href="item.admin.applications_url" class="btn btn-warning btn-outline btn-animate btn-animate-side">
											<span><i class="icon md-account-box-mail" aria-hidden="true"></i>Applications</span>
										</a>
										<a v-bind:href="item.admin.verify_hours_url" class="btn btn-success btn-outline btn-animate btn-animate-side">
											<span><i class="icon md-time" aria-hidden="true"></i>Verify Hours</span>
										</a>
									</td>
								</tr>
							</template>
							<div class="list-item no-results" v-show="!items.length && !loading">
								<h1 class="grey-400">It looks like you haven't created any opportunities yet...</h1>
								<p>Start here to begin creating your volunteer workforce!</p>
								<a href="{{ route('nonprofits.opportunities.create', $authNonprofit->id) }}" class="btn btn-success btn-lg">Create A New Opportunity</a>
							</div>
						</tbody>
					</table>
					<div class="text-center" v-show="loading">
						@include('app.components.loading')
					</div>
					<div class="text-center" v-show="!loading && nextPageUrl">@include('app.components.feeds.load-more')</div>
				</div>
			</div>
		</div>
	</div>
</div>
</simple-feed>
@stop