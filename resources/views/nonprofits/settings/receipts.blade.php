@extends('volunteers.layout')

@section('page_id', 'settings')

@section('page-header')
	<div class="page-header">
		<h2>Payment Receipts</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('volunteers.settings._sidebar')
		<div class="single-column single-column--box">
			<section>
				<h3>Receipts</h3>
				<ul class="list">
					@forelse($invoices as $invoice)
					<li class="list-item flex-columns">
						<div class="column column--33">{{ $invoice->date()->toFormattedDateString() }}</div>
						<div class="column column--33">{{ $invoice->total() }}</div>
						<div class="column column--33">
							<a href="{{ route('volunteers.settings.receipts.download', $invoice->id) }}" class="btn btn--default btn--small btn--block">Download Receipt</a>
						</div>
					</li>
					@empty
						<li class="list-item no-results">You don't have any receipts.</li>
					@endforelse
				</ul>
			</section>
		</div>
	</div>
@stop