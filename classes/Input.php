<?php 

class Input 
{
	public static function exists($type = 'post'){
		switch ($type) {
			case 'post':
				return (!empty($_POST) && isset($_POST)) ? true : false;
				break;

			case 'get':
				return (!empty($_GET) && isset($_GET)) ? true : false;
				break;			
			default:
				return false;
				break;
		}
	}



	public static function get($item) {
		if (isset($_POST[$item])) {
			return $_POST[$item];
		}else if(isset($_GET[$item])) {
			return $_GET[$item];
		}

		return '';
		
	}

	public static function check($item) {
		if (isset($_POST[$item])) {
			return true;
		}else if(isset($_GET[$item])) {
			return true;
		}
		else if($_FILES[$item]['name'] !== "") {
			return true;
		}

		return false;
		
	}
}