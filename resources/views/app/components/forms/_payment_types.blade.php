<div class="form-field form-group">
    <div class="col-xs-1">
        {!! Form::label('donation_type', 'One time:') !!}
        {{ Form::radio('donation_type', 'one-time') }}
    </div>
    <div class="col-xs-1">
        {!! Form::label('donation_type', 'Daily:') !!}
        {{ Form::radio('donation_type', 'daily') }}
    </div>
    <div class="col-xs-1">
        {!! Form::label('donation_type', 'weekly:') !!}
        {{ Form::radio('donation_type', 'monthly') }}
    </div>
    <div class="col-xs-1">
        {!! Form::label('donation_type', 'Monthly:') !!}
        {{ Form::radio('donation_type', 'monthly') }}
    </div>
    <div class="col-xs-1">
        {!! Form::label('donation_type', 'Yearly:') !!}
        {{ Form::radio('donation_type', 'yearly') }}
    </div>
</div>
