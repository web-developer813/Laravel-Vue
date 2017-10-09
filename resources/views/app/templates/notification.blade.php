<div v-bind:class="class" v-if="show" transition="fadeout" @click="show = false" role="alert" v-cloak>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">×</span>
	</button>
	<slot></slot>
</div>