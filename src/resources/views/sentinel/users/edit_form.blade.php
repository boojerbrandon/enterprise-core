@extends($layout)

{{-- Page content --}}
@section('content')

<div class="page-header">
	<h1>Update User <small>{{ $user->first_name }} {{ $user->last_name }}</small></h1>
</div>

<form method="post" action="" autocomplete="off">

	<div class="form-group{{ $errors->first('first_name', ' has-error') }}">

		<label for="first_name">First Name</label>

		<input type="text" class="form-control" name="first_name" id="first_name" value="{{ Input::old('first_name', $user->first_name) }}" placeholder="Enter the user first_name.">

		<span class="help-block">{{{ $errors->first('first_name', ':message') }}}</span>

	</div>

	<div class="form-group{{ $errors->first('last_name', ' has-error') }}">

		<label for="name">Last Name</label>

		<input type="text" class="form-control" name="last_name" id="last_name" value="{{ Input::old('last_name', $user->last_name) }}" placeholder="Enter the user last_name.">

		<span class="help-block">{{{ $errors->first('last_name', ':message') }}}</span>

	</div>

	<div class="form-group{{ $errors->first('email', ' has-error') }}">

		<label for="email">Email</label>

		<input type="text" class="form-control" name="email" id="email" value="{{ Input::old('email', $user->email) }}" placeholder="Enter the user email.">

		<span class="help-block">{{{ $errors->first('email', ':message') }}}</span>

	</div>

	<div class="form-group{{ $errors->first('password', ' has-error') }}">

		<label for="password">Password</label>

		<input type="password" class="form-control" name="password" id="password" value="" placeholder="Enter the user password (only if you want to modify it).">

		<span class="help-block">{{{ $errors->first('password', ':message') }}}</span>

	</div>

	@if ($all_roles->count())
		<div class="form-group">
			<h3>Roles</h3>
			<ul class="list-unstyled">
				@foreach ($all_roles as $role) 
					<li>
						<label class="checkbox-inline">
							<input type="checkbox" name="roles[{{ $role->id }}]" value='1' {{ ($user->inRole($role->id) ? 'checked="checked"' : '') }}>
							{{ $role->name }}	
						</label>
					</li>
				@endforeach
			</ul>
		</div>
	@endif	

	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	
	<hr>
	<button type="submit" class="btn btn-success">Submit</button> <a href="{{ URL::route('admin_users') }}" class="btn btn-default">Cancel</a>

</form>

@stop