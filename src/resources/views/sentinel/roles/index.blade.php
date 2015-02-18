@extends('enterpriseCore::admin.layout')

{{-- Page content --}}
@section('content')

<div class="page-header">
	<h1>Roles <span class="pull-right"><a href="{{ URL::route('admin_create_roles') }}" class="btn btn-warning">Create</a></span></h1>
</div>

@if ($roles->count())
Page {{ $roles->currentPage() }} of {{ $roles->lastPage() }}

<div class="pull-right">
	{!! $roles->render() !!}
</div>

<br><br>

<table class="table table-bordered">
	<thead>
		<th class="col-lg-6">Name</th>
		<th class="col-lg-4">Slug</th>
		<th class="col-lg-2">Actions</th>
	</thead>
	<tbody>
		@foreach ($roles as $role)
		<tr>
			<td>{{ $role->name }}</td>
			<td>{{ $role->slug }}</td>
			<td>
				<a class="btn btn-warning" href="{{ URL::route('admin_edit_roles', $role->id) }}">Edit</a>
				<a class="btn btn-danger" href="{{ URL::route('admin_delete_roles', $role->id) }}">Delete</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

Page {{ $roles->currentPage() }} of {{ $roles->lastPage() }}

<div class="pull-right">
	{!! $roles->render() !!}
</div>
@else
<div class="well">

	Nothing to show here.

</div>
@endif

@stop
