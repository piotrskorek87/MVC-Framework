@extends('../app/views/template/template.php')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-5">
				@include('../app/views/user/partials/userblock.php')
				<hr>

				@if(!$statuses)
					<p><?php echo $user->getNameOrUsername(); ?> hasn't posted anything yet.</p>
				@else
					@foreach($statuses as $status)
						<div class="media">
							<a class="pull-left" href="{{ route('profile/index', ['username' => $status->user()->getNameOrUsername()]) }}">
								<img class="media-object" src="{{ $status->user()->getAvatarUrl() }}" alt="{{ $status->user()->getNameOrUsername() }}">
							</a>
							<div class="media-body">
								<h4 class="media-heading"><a href="{{ route('profile/index', ['username' => $status->user()->getNameOrUsername()]) }}">{{ $status->user()->getNameOrUsername() }}</a></h4>
								<p>{{ $status->body }}</p>
								<ul class="list-inline">
									<li>{{ $status->created_at }}</li>
									@if(!$status->user()->id !== $auth->user()->id)
										<li><a href="{{ route('status/like', ['statusId' => $status->id]) }}">Like</a></li>
									@endif
									<li>{{ $status->likes()->count() }}Likes</li>
								</ul>
								
								@if($status->replies())
									@foreach($status->replies() as $reply)
										<div class="media">
											<a class="pull-left" href="{{ route('profile/index', ['username' => $reply->user()->getUsername()]) }}">
												<img class="media-object" src="{{ $reply->user()->getAvatarUrl() }}" alt="{{ $reply->user()->getNameOrUsername() }}">
											</a>
											<div class="media-body">
												<h5 class="media-heading"><a href="{{ route('profile/index', ['username' => $reply->user()->getUsername()]) }}">{{ $reply->user()->getNameOrUsername() }}</a></h5>
												<p>{{ $reply->body }}</p>
												<ul class="list-inline">
													<li>{{ $reply->created_at }}</li>
													@if(!$reply->user()->id !== $auth->user()->id)
														<li><a href="{{ route('status/like', ['statusId' => $reply->id]) }}">Like</a></li>
													@endif
													<li>{{ $reply->likes()->count() }} Likes</li>
												</ul>
											</div>
										</div>
									@endforeach
								@endif

								@if($authUserIsFriend ||  $auth->user()->id === $status->user()->id)
									<form role="form" action="{{ route('status/reply', ['statusId' => $status->id]) }}" method="post">
										<div class="form-group {{ $errors->has("reply-{$status->id}") ? ' has-error' : '' }}">
											<textarea name="reply-{{ $status->id }}" class="form-control" rows="2" placeholder="Reply to this status"></textarea>
											@if($errors->has("reply-{$status->id}"))
													<span class="help-block">{{ $errors->first("reply-{$status->id}") }}</span>
												
											@endif
										</div>
										<input type="submit" value="Reply" class="btn btn-default btn-sm">
										<input type="hidden" name="_token" value="{{ Token::generate() }}">
									</form>
								@endif
							</div>
						</div>
					@endforeach
				@endif
			</div>
			<div class="col-lg-4 col-lg-offset-3">

				@if($auth->user()->hasFriendRequestPending($user))
					<p>Waiting for {{ $user->getUsername() }} to accept your request.</p>

	 			@elseif($auth->user()->hasFriendRequestReceived($user))
	 				<a href="{{ route('friend/accept', ['username' => $user->getUsername()]) }}" class="btn btn-primary">Accept friend request</a>
				
				@elseif($auth->user()->isFriendsWith($user))
					<p>You and {{ $user->getUsername() }} are friends.</p>
					<form action="{{ route('friend/delete', ['username' => $user->getUsername()]) }}" method="post" >
						<input type="submit" value="Delete friend" class="btn btn-primary">
						<input type="hidden" name="_token" value="{{ Token::generate() }}">
					</form>

				@elseif($auth->user()->id !== $user->id)
					<a href="{{ route('friend/add', ['username' => $user->getUsername()]) }}" class="btn btn-primary">Add as friend</a>
				
				@endif

				<h4>{{ $user->getNameOrUsername() }}'s friends.</h4>

				@if(!$friends)
					<p>{{ $user->getNameOrUsername() }} has no friends.</p>
				@else
					@foreach($friends as $user)
	 					@include('../app/views/user/partials/userblock.php')
					@endforeach	
				@endif				
			</div>
		</div>
	</div>
@stop

