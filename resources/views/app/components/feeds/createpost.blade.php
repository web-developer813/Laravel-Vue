{{ Form::open(['route' => 'post.create', 'novalidate', 'class' => 'app-message-input']) }}
@if(count($errors) > 0)
<div class="form-group form-material floating has-error">
@else
<div class="form-group form-material floating">
@endif
	<div class="input-group form-material">
		<span class="input-group-btn">
			<a href="javascript: void(0)" class="btn btn-pure btn-default icon md-camera"></a>
		</span>
		<textarea class="form-control" rows="1" name="content" placeholder="Start sharing your story here ..." data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-notempty-message="This field is required" data-fv-stringlength-message="We'd like to know a little more info...tell a bit more of your story!" data-fv-field="vMinLength"></textarea>
		<span class="input-group-btn">
			<button type="submit" class="btn btn-pure btn-default icon md-mail-send"></button>
		</span>
	</div>
	<small class="help-block" data-fv-validator="stringLength" data-fv-for="vMinLength" data-fv-result="NOT_VALIDATED" style="display: none;">We'd like to know a little more info...tell a bit more of your story!</small>
	@if(Session::has('message'))
		<small class="help-block success">{{Session::get('message')}}</small>
	@endif
</div>
{{ Form::close() }}
