{{ Form::open([
	'route' => 'opportunities.index', 'method' => 'get', 'class' => 'form--list',
	'onsubmit' => 'return false'
]) }}
	<div class="feed-filters feed-filters--bordered flex-columns">
		{{-- search --}}
		<div class="filters-group column column--50 column--grow-1">
			<div class="field--text">
				{{ Form::text('search', null, [
					'placeholder' => 'Keyword Search...', 'v-model' => 'search', 'debounce' => 500
				]) }}
			</div>
		</div>
		{{-- filters --}}
		<div class="filters-group column">
			@include('app.components.checkbox-toggle', [
				'field' => 'virtual', 'label' => 'Only&nbsp;Remote&nbsp;Work', 'value' => false, 'v_model' => 'virtual'
			])
		</div>
		<div class="filters-group column">
			@include('app.components.checkbox-toggle', [
				'field' => 'flexible', 'label' => 'Only&nbsp;Flexible&nbsp;Dates', 'value' => false, 'v_model' => 'flexible'
			])
		</div>
	</div>
{{ Form::close() }}