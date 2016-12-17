<?php
function route($string, $params = []) {
	if(!empty($params)){
		$string = strtolower($string .= '/'.implode('/', $params));
	}
	// echo '../'. $string;

	return "http://localhost/mvc/public/". $string;
}