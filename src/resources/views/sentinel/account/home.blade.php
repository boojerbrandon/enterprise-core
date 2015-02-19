@extends($layout)

@section('title')
My Account
@stop

@section('content')

	<div class="page-header">
		<h1>My Account</h1>
	</div>

	<div class="row">
		<div class="col-md-6">
			<h3>
				My Logged In Sessions
				<small>Click to Kill</small>
			</h3>

			<div class="list-group">
				<a href="{{ URL::route('admin_kill') }}" class="list-group-item">Kill all <small>(including my session)</small></a>
				<a href="{{ URL::route('admin_kill_all') }}" class="list-group-item">Kill all <small>(excluding my session)</small></a>
			</div>

			<div class="list-group">

				@foreach ($user->persistences as $index => $p)
					@if ($p->code === $persistence->check())
						<a href="{{ URL::route('admin_kill_session_key', $p->code) }}" class="list-group-item active">
							{{ $p->created_at->format('F d, Y - h:ia') }}
							<span class="label label-info">{{ $p->browser }}</span>
							<span class="badge">{{ $p->last_used }}</span>
							<span class="badge">You</span>
						</a>
					@else
						<a href="{{ URL::route('admin_kill_session_key', $p->code) }}" class="list-group-item">
							{{ $p->created_at->format('F d, Y - h:ia') }}
							<span class="label label-info">{{ $p->browser }}</span>
							<span class="badge">{{ $p->last_used }}</span>
						</a>
					@endif
				@endforeach
			</div>

		</div>

		<div class="col-md-6">
			<h3>Activation</h3>

			@if (Activation::completed($user))

				<a class="btn btn-danger" href="{{ URL::route('admin_deactivate') }}">Deactivate</a>

			@else

				<a class="btn btn-default" href="{{ URL::route('admin_reactivate') }}">Activate</a>

			@endif
		</div>


@stop
