@extends('../app/views/template/template.php')

@section('content')
	<div class="container">
		<h3>Sign in</h3>
		<div class="row">
			<div class="col-lg-6">
				<form class="form-vertical" action="<?php route('auth/signin');?>" method="post">
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

						<div class="checkbox">
							<label for="remember" class="control-label">
								<input type="checkbox" name="remember" id="remember">Remember me
							</label>
						</div>

					<input type="hidden" name="token" value="{{ Token::generate() }}">
					<input type="submit" class="btn btn-default" value="Sign in">
				</form>
			</div>
		</div>
	</div>
@stop


	


