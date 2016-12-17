<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="http://localhost/mvc/public/css/bootstrap.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7">
</head>
<body>
	<nav class="navbar navbar-default" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo  route('home/index') ; ?>">Chatty</a>
		</div>
		<div class="collapse navbar-collapse">
			
				<ul class="nav navbar-nav">
					<?php if($auth->check()): ?>
						<li><a href="<?php echo  route('home/index') ; ?>">Timeline</a></li>
						<li><a href="<?php echo  route('friend/index') ; ?>">Friends</a></li>
					<?php endif; ?>
				</ul>
					<?php if($auth->check()): ?>
				<form class="navbar-form navbar-left" role="search" action="<?php echo  route('search/results') ; ?>">
					<div class="form-group">
						<input type="text" name="query" class="form-control" placeholder="Find people">
					</div>
					<button type="submit" class="btn btn-default">Search</button>					
				</form>
					<?php endif; ?>
			
			<ul class="nav navbar-nav navbar-right">
	
				<?php if($auth->check()): ?>
					<li><a href="<?php echo  route('profile/index', ['username' => $auth->user()->getUsername()]) ; ?>"><?php echo  $auth->user()->getUsername() ; ?></a></li>
					<li><a href="<?php echo  route('profile/edit', ['username' => $auth->user()->getUsername()]) ; ?>">Update profile</a></li>
					<li><a href="<?php echo  route('auth/signout') ; ?>">Sign out</a></li>
				<?php endif; ?>

				<?php if(!$auth->check()): ?>
					<li><a href="<?php echo  route('auth/signup') ; ?>">Sign up</a></li>	
					<li><a href="<?php echo  route('auth/signin') ; ?>">Sign in</a></li>	
				<?php endif; ?>
					
			</ul>
		</div>
	</div>
</nav>

	<div class="container">
		<?php if(Session::exists('info')): ?>
				<div class="alert alert-info" role="alert">
	<?php echo  Session::flash('info') ; ?>			
</div>
		<?php endif; ?>
	</div>




	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<h3>Your friends</h3>

				<?php if(!$friends): ?>
					<p>You have no friends.</p>
				<?php else: ?>
					<?php foreach($friends as $user): ?>
						<div class="media">
	<a class="pull-left" href="<?php echo  route('profile/index', ['username' => $user->getUsername()]) ; ?>">
		<img class="media-object" alt="<?php echo  $user->getUsername() ; ?>" src="<?php echo  $user->getAvatarUrl() ; ?>"> 
	</a>
	<div class="media-body">
		<h4 class="media-heading"><a href="<?php echo  route('profile/index', ['username' => $user->getUsername()]) ; ?>"><?php echo  $user->getUsername() ; ?></a></h4>
		<?php if($user->location): ?>
			<p><?php echo  $user->location ; ?></p>
		<?php endif; ?>
	</div>	
</div>
					<?php endforeach; ?>						
				<?php endif; ?>	
			</div>
			<div class="col-lg-6">
				<h4>Friend requests</h4>

				<?php if(!$friendRequests): ?>
					<p>You have no friend requests.</p>
				<?php else: ?>
					<?php foreach($friendRequests as $user): ?>
						<div class="media">
	<a class="pull-left" href="<?php echo  route('profile/index', ['username' => $user->getUsername()]) ; ?>">
		<img class="media-object" alt="<?php echo  $user->getUsername() ; ?>" src="<?php echo  $user->getAvatarUrl() ; ?>"> 
	</a>
	<div class="media-body">
		<h4 class="media-heading"><a href="<?php echo  route('profile/index', ['username' => $user->getUsername()]) ; ?>"><?php echo  $user->getUsername() ; ?></a></h4>
		<?php if($user->location): ?>
			<p><?php echo  $user->location ; ?></p>
		<?php endif; ?>
	</div>	
</div>
					<?php endforeach; ?>						
				<?php endif; ?>
			</div>
		</div>
	</div>





</body>
</html>