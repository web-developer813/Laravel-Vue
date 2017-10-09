@extends('admin.layout')

@section('content')
	<div class="container">
		<h1 class="page-title">Nonprofits</h1>
		
		<div class="row">
			<div class="col-md-4">
				<div class="filters">
					{{ Form::open(['route' => 'admin.nonprofits.index', 'method' => 'get']) }}
						<div class="form-field">
							<label>Verified</label>
							{{ Form::select('verified', [null => 'Select', 1 => 'Verified', 0 => 'Pending Verification'], request()->get('verified')) }}
						</div>
						<div class="form-field">
							{{ Form::submit('Filter', ['class' => 'btn btn--primary']) }}
						</div>
					{{ Form::close() }}
				</div>
			</div>
			<div class="col-md-8">
				<div class="table-wrapper">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Aproved</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
							@foreach($nonprofits as $nonprofit)
								<tr>
									<td>{{ $nonprofit->name }}</td>
									<td>{{ $nonprofit->verified }}</td>
									<td><a href="{{ route('admin.nonprofits.edit', $nonprofit->id) }}">Edit</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				{{ $nonprofits->links() }}
			</div>
		</div>
	</div>
@stop