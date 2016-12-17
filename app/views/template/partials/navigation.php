<nav class="navbar navbar-default" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ route('home/index') }}">Chatty</a>
		</div>
		<div class="collapse navbar-collapse">
			
				<ul class="nav navbar-nav">
					@if($auth->check())
						<li><a href="{{ route('home/index') }}">Timeline</a></li>
						<li><a href="{{ route('friend/index') }}">Friends</a></li>
					@endif
				</ul>
					@if($auth->check())
				<form class="navbar-form navbar-left" role="search" action="{{ route('search/results') }}">
					<div class="form-group">
						<input type="text" name="query" class="form-control" placeholder="Find people">
					</div>
					<button type="submit" class="btn btn-default">Search</button>					
				</form>
					@endif
			
			<ul class="nav navbar-nav navbar-right">
	
				@if($auth->check())
					<li><a href="{{ route('profile/index', ['username' => $auth->user()->getUsername()]) }}">{{ $auth->user()->getUsername() }}</a></li>
					<li><a href="{{ route('profile/edit', ['username' => $auth->user()->getUsername()]) }}">Update profile</a></li>
					<li><a href="{{ route('auth/signout') }}">Sign out</a></li>
				@endif

				@if(!$auth->check())
					<li><a href="{{ route('auth/signup') }}">Sign up</a></li>	
					<li><a href="{{ route('auth/signin') }}">Sign in</a></li>	
				@endif
					
			</ul>
		</div>
	</div>
</nav>
