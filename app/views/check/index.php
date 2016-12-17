
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
<script>

	function ajaxObj( meth, url ) {
		var x = new XMLHttpRequest();
		x.open( meth, url, true );
		x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		return x;
	}
	function ajaxReturn(x){
		if(x.readyState == 4 && x.status == 200){
		    return true;	
		}
	}


	function check(){

		var ajax = ajaxObj("post", "<?php route('home/checkkk'); ?>");
		ajax.onreadystatechange = function() {
			if(ajaxReturn(ajax) == true) {
				alert(ajax.responseText);

			}
		}
		ajax.send('user=user');

	}	

</script>

</head>
<body>
	<p>Hello hello</p>
	<button onclick="check()">Unblock User</button>
</body>
</html>