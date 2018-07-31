<h3 class="edit-number"></h3>
	<div class="form-group">
		{!! Form::label('description', 'Lead source description:') !!}
		{!! Form::text('edit-description') !!}
	</div>
	<div class="form-group">
		{!! Form::label('forwarding_number', 'Lead forwarding number:') !!}
		{!! Form::text('edit-forwarding-number') !!}
	</div>
	{!! Form::button('Save', ['class' => 'btn btn-primary ajax-save']) !!}

{!! Form::button('Delete this number', ['class' => 'btn btn-danger ajax-delete']) !!}