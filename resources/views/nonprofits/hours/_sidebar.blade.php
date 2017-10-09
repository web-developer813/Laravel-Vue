<aside class="sidebar">
	{{-- applications --}}
	<h3 class="sidebar__title sidebar__title--first">Total Applications</h3>
	<div class="sidebar__points">{{ number_format($authNonprofit->applications->count()) }}</div>
	{{-- opportunities --}}
	<h3 class='sidebar__title'>Opportunities</h3>
	<ul class="sidebar__section">
		<li class="section__item"><a href="#">All</a></li>	
		@foreach($opportunities as $opportunity)
			<li class="section__item"><a href="#">{{ $opportunity->title }}</a></li>	
		@endforeach
	</ul>
</aside>