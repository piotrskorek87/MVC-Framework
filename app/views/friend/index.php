@extends('../app/views/template/template.php')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<h3>Your friends</h3>

				@if(!$friends)
					<p>You have no friends.</p>
				@else
					@foreach($friends as $user)
						@include('../app/views/user/partials/userblock.php')
					@endforeach						
				@endif	
			</div>
			<div class="col-lg-6">
				<h4>Friend requests</h4>

				@if(!$friendRequests)
					<p>You have no friend requests.</p>
				@else
					@foreach($friendRequests as $user)
						@include('../app/views/user/partials/userblock.php')
					@endforeach						
				@endif
			</div>
		</div>
	</div>
@stop


