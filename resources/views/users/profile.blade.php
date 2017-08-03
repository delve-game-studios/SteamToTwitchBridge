@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('storage/service_icons.css') }}">
<div class="container">
	@include('partials.flash')
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Profile</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-5">
							<div class="panel panel-default">
								<div class="panel-heading">Profile Details</div>
								<div class="panel-body">
									<div class="row">
										<table class="table col-md-12">
											<tr>
												<th>Full Name: </th>
												<td>{{ $user->name }}</td>
											</tr>
											<tr>
												<th>E-Mail: </th>
												<td>{{ $user->email }}</td>
											</tr>
											<tr>
												<th>User Type: </th>
												<td>{{ $user->getRole() }}</td>
											</tr>
										</table>
									</div>
									<!-- <div class="row"><hr></div>
									<div class="row">
										<div class="col-md-12">
											<h4>Change Password</h4>
											<hr>
											<form class="form" action="" method="">
												{{csrf_field()}}
												<div class="row">
													<div class="col-md-5">
														<span>Password: </span>
													</div>
													<div class="col-md-7">
														<input type="password" name="password" class="form-control">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col-md-5">
														<span>Confirm Password: </span>
													</div>
													<div class="col-md-7">
														<input type="password" name="confirm_password" class="form-control">
													</div>
												</div>
												<hr>
												<div class="row">
													<div class="col-md-4"></div>
													<div class="col-md-4">
														<button class="btn btn-primary" role="submit" type="submit">Update</button>
													</div>
												</div>
											</form>
										</div>
									</div> -->
								</div>
							</div>
						</div>
						<div class="col-md-7">
							<div class="panel panel-default">
								<div class="panel-heading">Connections</div>
								<div class="panel-body">
									<div class="row">
									@foreach($services as $service)
										@if(empty($service['hidden']))
										<div class="col-md-3">
											@if(!empty($service['connected']))
											<div class="service {{ $service['slug'] }} connected" data-toggle="modal" data-target="#{{ $service['slug'] }}">
												<div class="service-icon"></div>
											</div>
											@else
											<div class="service {{ $service['slug'] }}" data-slug="{{ $service['slug'] }}">
												<div class="service-icon"></div>
											</div>
											@endif
										</div>
										@endif
									@endforeach
									</div>
								</div>
							</div>
						</div>
						@foreach($services as $service)
							@if(!empty($service['connected']))
								@include('modals.connection-settings')
							@endif
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')

<script type="text/javascript" class="removeMe">

	$(document).on('click', 'div.service:not(.connected)', function() {
		var $slug = $(this).data('slug');
		var services = {};
		@foreach($services as $i => $service)
		services["{{ $service['slug'] }}"] = "{{ route('services.auth.' . $service['slug']) }}";
		@endforeach
		window.open(services[$slug], '_self');
	});

	$('.removeMe').remove();
</script>

@endsection