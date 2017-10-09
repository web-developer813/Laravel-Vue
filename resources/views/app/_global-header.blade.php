@if(current_mode() == 'nonprofit')
	@include('nonprofits._header')
	@include('nonprofits._sidemenu')
@elseif(current_mode() == 'forprofit')
	@include('forprofits._header')
	@include('forprofits._sidemenu')
@else
	@include('volunteers._header')
	@include('volunteers._sidemenu')
@endif