@if(Session::has('success'))
	<div class="alert alert-success">
		{{ Session::get('success') }}
	</div>
@endif

@if(Session::has('info'))
	<div class="alert alert-info">
		{{ Session::get('info') }}
	</div>
@endif

@if(Session::has('warning'))
	<div class="alert alert-warning">
		{{ Session::get('warning') }}
	</div>
@endif

@if(Session::has('error'))
	<div class="alert alert-danger">
		{{ Session::get('error') }}
	</div>
@endif

@if(Session::has('signin-permanent') && !!Auth::guest())
	<div class="alert alert-danger keep-me">
		You need to <a href="{{ route('login') }}">Sign In</a> {{ Session::get('signin-permanent') }}
	</div>
@endif

@if(Session::has('meessage'))
	<div class="alert alert-info">
		{{ Session::get('meessage') }}
	</div>
@endif

<script type="text/javascript">
	setTimeout(function(){
		$('div.alert:not(.keep-me)').hide('fade');
	}, 4000);
</script>