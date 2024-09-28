<fieldset class="form-group col-md-6">
	{{ Form::label('class_id', getphrase('Lớp học')) }}
	<!-- <span class="text-red">*</span> -->
	<?php 
	$select = (isset($record->parent_id)) ? $record->parent_id : null;
	?>
	{{Form::select('class_id', $class, $select, [ 'class'=>'form-control'])}}
</fieldset>

<fieldset class="form-group col-md-6">
	{{ Form::label('categories_id', getphrase('Khóa học')) }}
	<!-- <span class="text-red">*</span> -->
	<?php 
	$select = (isset($record->parent_id)) ? $record->parent_id : null;
	?>
	{{Form::select('categories_id', $categories, $select, [ 'class'=>'form-control'])}}
</fieldset>

<div class="buttons text-center">
	<button class="btn btn-lg btn-success button"
	ng-disabled='!formLms.$valid'>{{ $button_name }}</button>
</div>
