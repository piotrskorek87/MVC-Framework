@extends('../app/views/template/template.php')

@section('content')
	<div class="container">
		<h3>Sign up</h3>
		<div class="row">
			<div class="col-lg-6">
				<form class="form-vertical" action="signup" method="post">
					<div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
						<label for="username" class="control-label">Username</label>
						<input type="text" name="username" class="form-control" id="username" value="{{ escape(Input::get('username')) }}" autocomplete="off">
						@if($errors->has('username'))
							<span class="help-block">{{ $errors->first('username') }}</span>
						@endif
					</div>

					<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
						<label for="password" class="control-label">Choose a password</label>
						<input type="password" name="password" class="form-control" id="password" value="">
						@if($errors->has('password'))
							<span class="help-block">{{ $errors->first('password') }}</span>
						@endif
					</div>

					<div class="form-group {{ $errors->has('password_again') ? ' has-error' : '' }}">
						<label for="password_again" class="control-label">Enter your password</label>
						<input type="password" name="password_again" class="form-control" id="password_again" value="">
						@if($errors->has('password_again'))
							<span class="help-block">{{ $errors->first('password_again') }}</span>	
						@endif
					</div>

					<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
						<label for="name" class="control-label">Your name</label>
						<input type="text" name="name" class="form-control" id="name" value="" autocomplete="off">
						@if($errors->has('name'))
							<span class="help-block">{{ $errors->first('name') }}</span>
						@endif
					</div>
					<input type="hidden" name="token" value="{{ Token::generate() }}">
					<input type="submit" class="btn btn-default" value="register">
				</form>
			</div>
		</div>
	</div>
@stop

