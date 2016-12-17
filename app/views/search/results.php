@extends('../app/views/template/template.php')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<h3>Your search for "{{ Input::get('query') }}"</h3>

				@if(!$users)
					<p>No results found, sorry</p>
				@else
					<div class="row">
						<div class="col-lg-12">
							<?php foreach ($users as $user): ?>
								@include('../app/views/user/partials/userblock.php')
							<?php endforeach; ?>	
						</div>
					</div>
				@endif	
			</div>
		</div>
	</div>
@stop




