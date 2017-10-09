{{ Form::open([
	'route' => 'nonprofits.index', 'method' => 'get', 'class' => 'sidebar__filters',
	'onsubmit' => 'return false'
]) }}
	<section class="filters__search form-field">
		<div class="field--text">
			{{ Form::text('search', null, [
				'placeholder' => 'Keyword Search...', 'v-model' => 'search', 'debounce' => 500
			]) }}
		</div>
	</section>
	<section>
		<span class="btn btn--block btn--search-options" @click="toggleOptions">
			Categories
			<i class="fa fa-caret-down fa-fw" aria-hidden="true"></i>
		</span>
		<div class="filters__categories" v-show="opened">
			@include('volunteers.components.feeds.filter-categories')
		</div>
	</section>
{{ Form::close() }}