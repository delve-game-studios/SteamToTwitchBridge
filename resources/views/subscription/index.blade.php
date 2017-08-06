@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<div class="thumbnail plan" style="height:400px;cursor:pointer;">
					<div class="captions text-center">
						<h1>One Month</h1>
						<p>some description</p>
						<br />
						<br />
						<br />
						<h1>One Month</h1>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="thumbnail plan" style="height:400px;cursor:pointer;">
					<div class="captions text-center">
						<h1>Six Months</h1>
						<p>some description</p>
						<br />
						<br />
						<br />
						<h1>One Month</h1>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="thumbnail plan" style="height:400px;cursor:pointer;">
					<div class="captions text-center">
						<h1>One Year</h1>
						<p>some description</p>
						<br />
						<br />
						<br />
						<h1>One Month</h1>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<small>By clicking on any of the subscription plans you agree to our Privacy Policy and Terms of Condition</small>
			</div>
		</div>
	</div>
</div>

@endsection