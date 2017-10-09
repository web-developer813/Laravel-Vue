@extends('forprofits.layout')

@section('body_class', 'animsition td-tables')

@section('page_id', 'forprofits-admin-incentives-index')

@section('page-header')
	<div class="page-header">
		<h2>Incentives</h2>
	</div>
@stop

@section('page-content')
<simple-feed
	search="{{ request()->input('search') }}"
	resource-url="{{ route('api.forprofits.incentives', $authForprofit->id) }}"
	no-results="There are no incentives matching your criteria."
	inline-template>
	<div class="page">
		<div class="page-main">
			<div class="page-header">
				<h1 class="page-title mb-10">Manage Incentives</h1>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ route('forprofits.incentives.index', $authForprofit->id) }}">Dashboard</a></li>
					<li class="breadcrumb-item active">Manage Incentives</li>
				</ol>
				<div class="page-header-actions">
					<div class="panel-search-form">
						<div class="form-group">
							<div class="input-search">
								<i class="input-search-icon md-search" aria-hidden="true"></i>
								<input type="text" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search incentives..." class="form-control">
								<button type="button" class="input-search-close icon md-close" aria-label="Close" @click="filters.search = ''"></button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="page-content container-fluid">
				<div class="panel">
					<div class="panel-heading">
						<h3 class="panel-title">{{ $authForprofit->name }} Opportunities</h3>
						<div class="panel-actions">
							<a href="{{ route('forprofits.incentives.create', $authForprofit->id) }}" role="button" class="btn btn-success btn-lg waves">
								<span style="color: white">New Incentive <i class="icon md-plus-circle-o" aria-hidden="true"></i></span>
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
							<tbody>
								<tr v-for="item in items">
									<td class="work-status">
										<span v-if="item.incentive.active" class="badge badge-success">active</span>
										<span v-if="!item.incentive.active" class="badge badge-warning">inactive</span>
									</td>
									<td>
										<strong><a :href="item.admin.edit_url">@{{ item.incentive.title }}</a></strong>
										<br>
										<small>@{{ item.incentive.price }} points</small>
									</td>
									<td>
										<a v-bind:href="item.admin.edit_url" class="btn btn-primary btn-outline btn-animate btn-animate-side">
											<span><i class="icon md-edit" aria-hidden="true"></i>Edit</span>
										</a>
										<a :href="item.admin.purchases_url"  class="btn btn-warning btn-outline btn-animate btn-animate-side">
											Purchases (@{{ item.admin.purchases_count }})
										</a>
									</td>
								</tr>
							</tbody>
							<div class="list-item no-results" v-show="!items.length && !loading">
								@{{ noResults }}
							</div>
							@include('app.components.loading')
							@include('app.components.feeds.load-more')
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	
</simple-feed>
@stop