@extends($layout)

{{-- Page content --}}
@section('content')

<div class="page-header">
	<h1>{{ $mode == 'create' ? 'Create Role' : 'Update Role' }} <small>{{ $mode === 'update' ? $role->name : null }}</small></h1>
</div>

<form method="post" action="">

	<div class="form-group{{ $errors->first('name', ' has-error') }}">

		<label for="name">Name</label>

		<input type="text" class="form-control" name="name" id="name" value="{{ Input::old('name', $role->name) }}" placeholder="Enter the role name.">

		<span class="help-block">{{{ $errors->first('name', ':message') }}}</span>

	</div>

	<div class="form-group{{ $errors->first('slug', ' has-error') }}">

		<label for="slug">Slug</label>

		<input type="text" class="form-control" name="slug" id="slug" value="{{ Input::old('slug', $role->slug) }}" placeholder="Enter the role slug.">

		<span class="help-block">{{{ $errors->first('slug', ':message') }}}</span>

	</div>

	@if (!empty($role->permissions))
		<h3>Permissions</h3>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Route</th>
					<th>Access</th>
				<tr>
			</thead>
			<tbody>
				@foreach($role->permissions as $name => $value)
					<tr>
						<td>{{ $name }}</td>
						<td>
							<label class="radio-inline">
								<input type="radio" name="permissions[{{ $name }}]" value='1' {{ ($value == 1 ? 'checked="checked"' : '') }}>
								Yes
							</label>
							<label class="radio-inline">
								<input type="radio" name="permissions[{{ $name }}]" value='0' {{ ($value != 1 ? 'checked="checked"' : '') }}>
								No
							</label>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	<hr>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	
	<button type="submit" class="btn btn-success">Submit</button> <a href="{{ URL::route('admin_roles') }}" class="btn btn-default">Cancel</a>

</form>

@stop

