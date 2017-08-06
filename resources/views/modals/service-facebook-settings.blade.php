<div class="modals">
	<div class="modal fade" id="{{ $service['slug'] }}" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{{ $service['title'] }} Settings</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('services.facebook.save') }}" method="POST">
						{{csrf_field()}}
					</form>
				</div>
				<div class="modal-footer">
					<div class="col-md-2">
						<button type="button" class="btn btn-primary btn-submit">Submit</button>
					</div>
					<div class="col-md-2 col-md-offset-8">
						<button type="button" class="btn btn-danger btn-unlink">Unlink</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script class="removeMe">
		$(function() {
			FB.api(
				'/{{$service["settings"]["access"]["userID"]}}/accounts',
				'GET',
				{
					access_token: '{{$service["settings"]["access"]["accessToken"]}}'
				},
				function(response) {
					if(!!response.data) {
						var form = $('#service-facebook.modal').find('form');
						for(var i = 0; i < response.data.length; i++) {
							var page = response.data[i];
							var input = '<input type="radio" name="page" value="' + page.id + '"';
							if(page.id.toString() == '{{$service["settings"]["settings"]["page"]}}') {
								input += ' checked="checked"';
							}
							input += '>&nbsp;' + page.name + '<br />'
							$(form).append($(input));
						}
					} else {
						console.log(response);
					}
				}
			);
		});
	</script>
</div>