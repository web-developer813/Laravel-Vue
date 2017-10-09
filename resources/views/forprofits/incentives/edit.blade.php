@extends('forprofits.layout')

@section('page_id', 'forprofits-incentives-edit')


@section('page-content')
    <?php
		if($incentive->case == "") {
		    $case = "";
			$quantity = "";
		}else{
		    $case = $incentive->case;
            $quantity = $incentive->quantity;
		}
		if($incentive->tag != "") {
		    $tag = $incentive->tag;
            $textTag = ucfirst($incentive->tag);
		}else{
            $tag = "";
		    $textTag = "";
		}

		if($incentive->active  ==  '0'){
			$incentive_active = false;
		}else{
			$incentive_active = true;
		}
		if($incentive->employee_specific == 0){
		    $employee_specific = false;
		}else{
            $employee_specific = true;
		}
    ?>
	<incentive-editor
		submit-url="{{ route('api.forprofits.incentives.update', [$authForprofit->id, $incentive->id]) }}"
		title="{{ $incentive->title }}"
		description="{{ $incentive->description }}"
		summary="{{ $incentive->summary }}"
		terms = "{{ $incentive->terms }}"
		how_to_use = "{{ $incentive->how_to_use }}"
		image_id = "{{ $incentive->image_id }}"
		coupon_code = "{{ $incentive->coupon_code }}"
		days_to_use = "{{ $incentive->days_to_use }}"
		quantity = "{{ $quantity }}"
		price = "{{ $incentive->price }}"
		employee_specific = "{{ $employee_specific }}"
		active = "{{ $incentive_active }}"
		tag = "{{ $tag }}"
		case = "{{ $case }}"
		text_tag = "{{ $textTag }}"
		inline-template>

		<div class="page">
			<div class="page-main">
				{{ Form::model($incentive, [
					'files' => true, 'autocomplete' => 'off', 'novalidate', 'method' => 'put',
					'class' => 'form--list', '@submit.prevent' => 'submitEditForm', 'v-cloak']) }}
					<div class="page-header">
						<h1 class="page-title mb-10"><span>Edit Incentive <strong>{{ $incentive->title }}</strong></span></h1>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('forprofits.incentives.index', $authForprofit->id) }}">Dashboard</a></li>
							<li class="breadcrumb-item active">Edit Incentives</li>
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
										@include('forprofits.incentives.edit._description')
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