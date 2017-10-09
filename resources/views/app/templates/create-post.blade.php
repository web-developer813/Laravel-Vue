<div class="panel panel-bordered">
	<form method="POST" v-bind:action="action" @keyup.enter="submit">
		<div class="panel-body">
			{{ csrf_field() }}
			<input type="hidden" name="_method" v-bind:value="method" />
			<div v-bind:class="hasDanger ? 'form-group form-material floating has-danger' : 'form-group form-material floating'" data-plugin="formMaterial">
				<textarea v-model="content" name="content" class="form-control" rows="1" v-bind:disabled="loading"></textarea>
				<label class="floating-label">Share Your Volunteering Experience...</label>
			</div>
		</div>
		<div class="panel-footer text-right">
		<!--	{{-- <div v-if="!image">
			    <h2>Select an image</h2>
			    <input type="file" @change="onFileChange">
			  </div>
			  <div v-else>
			    <img :src="image" />
			    <button @click="removeImage">Remove image</button>
			</div> --}} -->
			   <input type="file" class="js-file-input" name="photo" id="input-file-now" data-plugin="dropify" data-max-file-size-preview="3M" data-allowed-file-extensions="jpg png jpeg" v-on:change="onFileChange" v-bind:data-default-file="image" > 
			<button type="button" class="btn btn-icon btn-success" ><i class="icon md-camera" aria-hidden="true" ></i></button>
			<button v-on:click.stop.prevent="createItem" type="submit" class="btn btn-icon btn-primary ladda-button" data-style="zoom-in">
				<span class="ladda-label"><i class="icon md-mail-send" aria-hidden="true"></i> Post</span>
			</button>
		</div>
	</form>
</div>
