<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="http://petersinclai.nazwa.pl/mvc/public/css/bootstrap.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7">
</head>
<body>
	@include('../app/views/template/partials/navigation.php')
	<div class="container">
		@if(Session::exists('info'))
				@include('../app/views/template/partials/alerts.php')
		@endif
	</div>

<!-- https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css -->