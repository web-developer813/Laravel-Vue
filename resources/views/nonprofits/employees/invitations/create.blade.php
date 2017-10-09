@extends('nonprofits.layout')

@section('page_id', 'invitations-create')

@section('page-header')
	<div class="page-header">
		<h2>Invite Employees</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('nonprofits.employees.index._sidebar')

		<div class="single-column">
			<section class="box">
				<h3>Upload a CSV</h3>
				<p>Upload a CSV with your employees emails.</p>
				
				<form
					action="{{ route('nonprofits.invitations.store_csv', $authNonprofit->id) }}"
					method="post"
					enctype="multipart/form-data"
					class="js-form">

					{!! csrf_field() !!}

					<input type="hidden" name="invitation_type" value="employee">
					
					<div class="form-field {{ form_error('csv_import') }}">
						<div class="field-wrapper">
							{{ Form::file('csv_import') }}
						</div>
						<div class="error-block">{{ $errors->first('csv_import') ?: '' }}</div>
					</div>

					<button type="submit" class="btn btn--primary">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Upload CSV
					</button>

				</form>
			</section>
			
			<section class="box">
				<h3>Individual Invitations</h3>
				<p>Enter the email addresses of the employees you wish to invite.</p>
				<p>Only enter one email address per line.</p>

				<form
					action="{{ route('nonprofits.invitations.store_emails', $authNonprofit->id) }}"
					method="post"
					class="js-form">

					{!! csrf_field() !!}

					<input type="hidden" name="invitation_type" value="employee">
					
					<div class="form-field {{ form_error('emails') }}">
						<div class="field-wrapper">
							<textarea name="emails" rows="10"></textarea>
						</div>
						<div class="error-block">{{ $errors->first('emails') ?: '' }}</div>
					</div>

					<button type="submit" class="btn btn--primary">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Upload Emails
					</button>

				</form>
			</section>
		</div>
	</div>
@stop