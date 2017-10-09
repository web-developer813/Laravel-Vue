@extends('forprofits.layout')

@section('page_id', 'forprofits-incentives-create')


@section('page-content')
	<incentive-editor
		submit-url="{{ route('api.forprofits.incentives.store', $authForprofit->id) }}"
		employee_specific = "{{ false }}"
		case = ""
		inline-template>
		<div class="page">
			<div class="page-main">
				{{ Form::open([
					'files' => true, 'autocomplete' => 'off', 'novalidate',
					'class' => 'form--list', '@submit.prevent' => 'submitCreateForm', 'v-cloak']) }}
				<div class="page-header">
					<h1 class="page-title mb-10"><span>New Incentive</span></h1>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('forprofits.incentives.index', $authForprofit->id) }}">Dashboard</a></li>
						<li class="breadcrumb-item active">New Incentives</li>
					</ol>
					<div class="page-header-actions">
						<button type="submit" class="btn btn-lg btn-success btn-round ml-30 ladda-button" data-style="zoom-in">
							<span class="ladda-label">Save <i class="icon md-edit" aria-hidden="true"></i></span>
						</button>
					</div>
				</div>
				<div class="page-content container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="panel">
								<div class="panel-body container-fluid">
									@include('forprofits.incentives.create._description')
								</div>
							</div>
						</div>
					</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>

	</incentive-editor>
@stop
@section('plugin_vendor')
	{!! script_cdn('/vendor/dropify/dropify.min.js') !!}
@stop