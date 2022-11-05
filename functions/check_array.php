<?php 

function check_array($string, $arr){
	if(array_key_exists($string, $arr)) {
        return true;
    }else {
        return false;
    }
}
