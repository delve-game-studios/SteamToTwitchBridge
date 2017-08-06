<div class="modals">
	<div class="modal fade" id="{{ $service['slug'] }}" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{{ $service['title'] }} Settings</h4>
				</div>
				<div class="modal-body">
					<form action="{{ route('services.save') }}" method="POST">
						{{csrf_field()}}
						@forelse($service['settings'] as $field => $value)
						<input type="text" name="{{ strtolower($field) }}" id="{{ $field }}" placeholder="{{ ucfirst(strtolower($field)) }}" style="width: 100%">
						@empty
						<p>No settings implemented for this service</p>
						@endforelse
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>
</div>