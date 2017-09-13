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
									<?php foreach($services as $key => $service) { ?>
										@if(empty($service['hidden']))
										<div class="col-md-3" <?= $key === 'service-steam' ? 'style="margin-left:12.4444444%;"' : ''?>>
											@if(!empty($service['connected']))
												@if($service['slug'] == 'service-facebook')
												<div class="service {{ $service['slug'] }} connected {{ !empty($service['settings']) ? 'settings' : '' }}" data-toggle="modal" data-target="#{{ $service['slug'] }}" data-unlink="{{ route(sprintf('services.%s.unlink', $service['slug'])) }}">
											@else
												<div class="service {{ $service['slug'] }} connected" data-unlink="{{ route(sprintf('services.%s.unlink', $service['slug'])) }}">
											@endif
													<div class="service-icon"></div>
												</div>
											@else
												<div class="service {{ $service['slug'] }} <?= !empty($service['disabled']) ? 'disabled' : '' ?>" data-slug="{{ $service['slug'] }}">
													<div class="service-icon"></div>
												</div>
											@endif
										</div>
										@endif
									<?php } ?>
									</div>
								</div>
							</div>
						</div>
						@foreach($services as $service)
							@if(!empty($service['connected']))
								<?php $modalViewName = sprintf('modals.%s-settings', $service['slug']) ?>
								@include($modalViewName)
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

	$(document).on('click', 'div.service:not(.connected):not(.service-facebook):not(.disabled)', function() {
		var $slug = $(this).data('slug');
		var services = {};
		@foreach($services as $i => $service)
		services["{{ $service['slug'] }}"] = "{{ route('services.auth.' . $service['slug']) }}";
		@endforeach
		window.open(services[$slug], '_self');
	});
	
</script>

@if(empty($services['service-facebook']['hidden']))
@endif

<script type="text/javascript" class="removeMe">
	$(document).on('click', '.modals .btn-success.btn-submit', function() {
		$(this).parents('.modal-content').find('form').submit();
	});
	$(document).on('click', 'button.close', '.modal', closeModal);
	$(document).on('click', 'button.btn-info', '.modal .modal-footer', closeModal);

	function closeModal() {
		for(var i = 0; i < window.closeModalTimeout.length; i++) {
			clearTimeout(window.closeModalTimeout[i]);
		}

		$parent = $(this).parents('.modal')[0];
		$form_footer = $('.form-footer', $parent);
		$form_body = $('.form-body', $parent);
		$flash_footer = $('.flash-footer', $parent);
		$flash_body = $('.flash-body', $parent);
		$($form_body).show();
		$($flash_body).hide();
		$($form_footer).show();
		$($flash_footer).hide();

	};
	$(document).on('submit', '.modals form', function() {
		var paramObj = {};
		var that = $(this);
		$.each($(this).serializeArray(), function(_, kv) {
		  paramObj[kv.name] = kv.value;
		});
		$.ajax({
			url: that.attr('action'),
			data: paramObj,
			method: 'POST',
			success: function(response) {
				$parent = $(that).parents('.modal')[0];
				$form_footer = $('.form-footer', $parent);
				$form_body = $('.form-body', $parent);
				$flash_footer = $('.flash-footer', $parent);
				$flash_body = $('.flash-body', $parent);

				$($form_body).hide();
				$('div.alert', $flash_body).attr('class', 'alert alert-' + response.code + ' keep-me');
				$('div.alert', $flash_body).text(response.message);
				$($flash_body).show();

				$($form_footer).hide();
				$($flash_footer).show();

				if(!!response.dump) {
					console.log(response.dump);
				}
				window.closeModalTimeout.push(setTimeout(function() {
					$('button.close', $parent).click();
					$($form_body).show();
					$($flash_body).hide();
					$($form_footer).show();
					$($flash_footer).hide();
				}, 4000));
			}
		});
		return false;
	});
	$(document).on('click', '.modals .btn-danger.btn-unlink', function() {
		$.post('{{route("services.service-facebook.unlink")}}', {
			_token: '{{csrf_token()}}'
		}, function() {
			location.reload();
		});
	});
	$(document).on('click', 'div.service.connected:not(.service-facebook)', function() {
		var $link = $(this).data('unlink');
		$.post($link, {
			_token: '{{csrf_token()}}'
		}, function() {
			location.reload();
		});
	});
</script>

<script type="text/javascript" class="removeMe">
	// $('.removeMe').remove();
</script>

@endsection