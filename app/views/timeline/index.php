@extends('../app/views/template/template.php')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<form role="form" action="{{ route('status/post') }}" method="post">
					<div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
						<textarea placeholder="What's up {{ $auth->user()->getNameOrUsername() }} ?" class="form-control" name="status" rows="2"></textarea>
						@if($errors->has('status'))
							<span class="help-block">{{ $errors->first('status') }}</span>	
						@endif
					</div>
					<button type="submit" class="btn btn-default">Update status</button>
					<input type="hidden" name="_token" value="{{ Token::generate() }}">
				</form>
				<hr>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-5">
				@if(!$statuses)
					<p>There's nothing in your timeline, yet.</p>
				@else
					@foreach($statuses as $status)
						<div class="media">
							<a class="pull-left" href="{{ route('profile/index', ['username' => $status->user()->getNameOrUsername()]) }}">
								<img class="media-object" src="{{ $status->user()->getAvatarUrl() }}" alt="{{ $status->user()->getNameOrUsername() }}">
							</a>
							<div class="media-body">
								<h4 class="media-heading"><a href="{{ route('profile/index', ['username' => $status->user()->getUsername()]) }}">{{ $status->user()->getNameOrUsername() }}</a></h4>
								<p>{{ $status->body }}</p>
								<ul class="list-inline">
									<li>{{ $status->created_at }}</li>
									@if(!$status->user()->id !== $auth->user()->id)
										<li><a href="{{ route('status/like', ['statusId' => $status->id]) }}">Like</a></li>
									@endif
									<li>{{ $status->likes()->count() }} Likes</li>
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
							</div>
						</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>
@stop

