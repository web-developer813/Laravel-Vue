<button
	type="submit"
	:disabled="submitting"
	v-bind:class="{'btn--loading': submitting}"
	class="btn btn--primary {{ $modifiers ?? '' }}">
	<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
	{{ $label ?? 'Submit' }}</button>